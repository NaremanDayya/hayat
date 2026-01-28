<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FamilySampleExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [
                'أحمد محمد علي', 
                '402345678', 
                'متزوج',
                'سارة محمود حسن',
                '402987654',
                '0599123456', 
                '1990-08-20',
                '5',
                'غزة - حي الشجاعية',
                'مخيم النويري - خيمة 45',
                '0599654321',
            ],
            [
                'مثال : يرجى ملء البيانات هنا',
                'رقم الهوية',
                'متزوج/أرمل/مطلق',
                'اسم الزوجة',
                'رقم هوية الزوجة',
                'رقم الجوال',
                'YYYY-MM-DD',
                'عدد الأفراد',
                'السكن الأصلي',
                'السكن الحالي',
                'رقم جوال الزوجة'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'اسم الزوج',
            'رقم هوية الزوج',
            'الحالة الاجتماعية',
            'اسم الزوجة',
            'رقم هوية الزوجة',
            'رقم هاتف الزوج',
            'تاريخ ميلاد الزوجة',
            'عدد أفراد الأسرة',
            'مكان السكن الأصلي',
            'مكان السكن الحالي',
            'رقم هاتف الزوجة',
        ];
    }
}
