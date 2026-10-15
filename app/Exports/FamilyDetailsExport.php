<?php

namespace App\Exports;

use App\Exports\Sheets\ArraySheet;
use App\Models\Family;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FamilyDetailsExport implements WithMultipleSheets
{
    public function __construct(protected Family $family)
    {
        $this->family->loadMissing(['members', 'healthConditions']);
        $this->family->healthConditions->each->setRelation('family', $this->family);
    }

    public function sheets(): array
    {
        return [
            $this->infoSheet(),
            $this->membersSheet(),
            $this->healthSheet(),
        ];
    }

    protected function infoSheet(): ArraySheet
    {
        $f = $this->family;

        return new ArraySheet('بيانات الأسرة', ['البيان', 'القيمة'], [
            ['اسم الزوج / ولي الأمر', $f->husband_name],
            ['رقم هوية الزوج', $f->husband_id_number],
            ['تاريخ ميلاد الزوج', $f->husband_dob],
            ['هاتف الزوج', $f->husband_phone],
            ['الحالة الاجتماعية', $f->marital_status],
            ['اسم الزوجة', $f->wife_name],
            ['رقم هوية الزوجة', $f->wife_id_number],
            ['تاريخ ميلاد الزوجة', $f->wife_dob],
            ['هاتف الزوجة', $f->wife_phone],
            ['السكن الأصلي', $f->original_address],
            ['السكن الحالي', $f->current_address],
            ['إجمالي عدد الأفراد', $f->calculated_members_count],
        ]);
    }

    protected function membersSheet(): ArraySheet
    {
        $f = $this->family;
        $rows = [];

        if (!empty($f->husband_name)) {
            $rows[] = $this->personRow('الزوج / ولي الأمر', 'ذكر', $f->husband_name, $f->husband_id_number, $f->husband_dob);
        }

        if (!empty($f->wife_name)) {
            $rows[] = $this->personRow('الزوجة', 'أنثى', $f->wife_name, $f->wife_id_number, $f->wife_dob);
        }

        foreach ($f->members as $member) {
            $isMale = $member->gender === 'male';
            $rows[] = $this->personRow(
                $isMale ? 'ابن' : 'ابنة',
                $isMale ? 'ذكر' : 'أنثى',
                $member->name,
                $member->id_number,
                $member->dob?->format('Y-m-d'),
            );
        }

        return new ArraySheet('أفراد الأسرة', ['الصفة', 'الجنس', 'الاسم', 'رقم الهوية', 'تاريخ الميلاد', 'العمر', 'الحالة الصحية'], $rows);
    }

    protected function healthSheet(): ArraySheet
    {
        $rows = $this->family->healthConditions->map(fn ($c) => [
            $c->person_name,
            $c->person_id_number,
            $c->condition_details,
        ])->all();

        return new ArraySheet('الحالات الصحية', ['اسم الشخص', 'رقم الهوية', 'طبيعة الحالة'], $rows);
    }

    protected function personRow(string $role, string $gender, ?string $name, ?string $idNumber, $dob): array
    {
        return [
            $role,
            $gender,
            $name,
            $idNumber,
            $dob,
            $dob ? Carbon::parse($dob)->age : '',
            $this->family->healthConditions->where('person_name', $name)->pluck('condition_details')->join('، '),
        ];
    }
}
