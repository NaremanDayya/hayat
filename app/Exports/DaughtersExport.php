<?php

namespace App\Exports;

use App\Models\FamilyMember;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DaughtersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return FamilyMember::femalesQuery($this->filters)->orderByDesc('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'رقم الهوية',
            'تاريخ الميلاد',
            'العمر',
            'الفئة',
            'الحالة الاجتماعية',
            'اسم الأب',
            'رقم هوية الأب',
            'رقم الجوال',
            'السكن الأصلي',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            " " . $row->id_number,
            $row->dob,
            $row->dob ? Carbon::parse($row->dob)->age : '',
            ['member' => 'ابنة', 'parent' => 'زوجة', 'head' => 'ربة أسرة'][$row->type] ?? '',
            $row->marital_status ?? '',
            $row->husband_name ?? '',
            " " . ($row->husband_id_number ?? ''),
            $row->husband_phone ?? '',
            $row->original_address ?? '',
        ];
    }
}
