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

class ChildrenTemplateSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    public function title(): string
    {
        return 'الأبناء والبنات';
    }

    public function headings(): array
    {
        return [
            'رقم الأسرة (من ورقة الأسر)',
            'الاسم الكامل',
            'رقم الهوية',
            'تاريخ الميلاد (YYYY-MM-DD)',
            'الجنس (ذكر / أنثى)',
        ];
    }

    public function array(): array
    {
        return [
            [1, 'علي أحمد محمد علي',    '405111111', '2005-03-12', 'ذكر'],
            [1, 'فاطمة أحمد محمد علي',  '405222222', '2008-07-25', 'أنثى'],
            [1, 'يوسف أحمد محمد علي',   '405333333', '2012-01-08', 'ذكر'],
            [2, 'مريم محمد خالد يوسف',  '404444444', '2010-11-30', 'أنثى'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF27AE60']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
