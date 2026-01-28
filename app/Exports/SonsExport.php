<?php

namespace App\Exports;

use App\Models\FamilyMember;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SonsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return FamilyMember::where('gender', 'male')->with('family')->get();
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'رقم الهوية',
            'تاريخ الميلاد',
            'اسم الأب',
            'رقم هوية الأب',
        ];
    }

    public function map($member): array
    {
        return [
            $member->name,
            " " . $member->id_number,
            $member->dob,
            $member->family->husband_name ?? '',
            " " . ($member->family->husband_id_number ?? ''),
        ];
    }
}
