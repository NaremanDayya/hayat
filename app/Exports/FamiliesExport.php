<?php

namespace App\Exports;

use App\Models\Family;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FamiliesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Family::all();
    }

    public function headings(): array
    {
        return [
            'اسم الزوج',
            'رقم هوية الزوج',
            'تاريخ ميلاد الزوج',
            'رقم هاتف الزوج',
            'الحالة الاجتماعية',
            'اسم الزوجة',
            'رقم هوية الزوجة',
            'تاريخ ميلاد الزوجة',
            'رقم هاتف الزوجة',
            'إجمالي عدد الأفراد',
            'مكان السكن الحالي',
        ];
    }

    public function map($family): array
    {
        return [
            $family->husband_name,
            " " . $family->husband_id_number, // Prefix with space to force text
            $family->husband_dob,
            $family->husband_phone,
            $family->marital_status,
            $family->wife_name,
            " " . $family->wife_id_number, // Prefix with space to force text
            $family->wife_dob,
            $family->wife_phone,
            $family->family_members_count,
            $family->current_address,
        ];
    }
}
