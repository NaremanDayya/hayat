<?php

namespace App\Exports;

use App\Models\Family;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FamiliesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Family::query()->with(['members', 'healthConditions']);

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('husband_name', 'like', '%' . $search . '%')
                  ->orWhere('wife_name', 'like', '%' . $search . '%')
                  ->orWhere('husband_id_number', 'like', '%' . $search . '%')
                  ->orWhere('wife_id_number', 'like', '%' . $search . '%')
                  ->orWhere('original_address', 'like', '%' . $search . '%')
                  ->orWhere('current_address', 'like', '%' . $search . '%')
                  ->orWhere('husband_phone', 'like', '%' . $search . '%')
                  ->orWhere('wife_phone', 'like', '%' . $search . '%');
            });
        }

        if (!empty($this->filters['hasDisease']) && $this->filters['hasDisease']) {
            $query->has('healthConditions');
        }

        if (!empty($this->filters['minAge']) || !empty($this->filters['maxAge'])) {
            $minAge = $this->filters['minAge'] ?? null;
            $maxAge = $this->filters['maxAge'] ?? null;

            $query->where(function($q) use ($minAge, $maxAge) {
                $startDate = $maxAge ? now()->subYears($maxAge)->startOfDay()->format('Y-m-d') : '1900-01-01';
                $endDate = $minAge ? now()->subYears($minAge)->endOfDay()->format('Y-m-d') : now()->format('Y-m-d');

                $q->whereBetween('husband_dob', [$startDate, $endDate])
                  ->orWhereBetween('wife_dob', [$startDate, $endDate]);
            });
        }

        return $query;
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
            'السكن الأصلي',
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
            $family->original_address,
            $family->current_address,
        ];
    }
}
