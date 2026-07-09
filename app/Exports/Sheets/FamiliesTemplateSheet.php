<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class FamiliesTemplateSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'الأسر';
    }

    public function headings(): array
    {
        return [
            'رقم الأسرة',
            'اسم الزوج / ولي الأمر',
            'رقم هوية الزوج',
            'تاريخ ميلاد الزوج (YYYY-MM-DD)',
            'رقم هاتف الزوج',
            'الحالة الاجتماعية (متزوج / أرمل / مطلق)',
            'اسم الزوجة',
            'رقم هوية الزوجة',
            'تاريخ ميلاد الزوجة (YYYY-MM-DD)',
            'رقم هاتف الزوجة',
            'مكان السكن الأصلي',
            'مكان السكن الحالي',
        ];
    }

    public function array(): array
    {
        return [
            [
                1,
                'أحمد محمد علي',
                '402345678',
                '1980-05-15',
                '0599123456',
                'متزوج',
                'سارة محمود حسن',
                '402987654',
                '1985-08-20',
                '0599654321',
                'غزة - حي الشجاعية',
                'مخيم النويري - خيمة 45',
            ],
            [
                2,
                'محمد خالد يوسف',
                '401112233',
                '1975-03-10',
                '0598765432',
                'أرمل',
                '',
                '',
                '',
                '',
                'غزة - رفح',
                'مخيم النويري - خيمة 12',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2C3E50']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
