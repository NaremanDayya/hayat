<?php

namespace App\Exports;

use App\Models\HealthCondition;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HealthConditionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = HealthCondition::with('family.members');

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('person_name', 'like', '%' . $search . '%')
                  ->orWhere('condition_details', 'like', '%' . $search . '%');
            });
        }

        if (!empty($this->filters['gender'])) {
            $query->filterByGender($this->filters['gender']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'اسم الفرد',
            'رقم الهوية',
            'الجنس',
            'تاريخ الميلاد',
            'العمر',
            'رقم الهاتف',
            'العنوان الأصلي',
            'العنوان الحالي',
            'الحالة الصحية',
        ];
    }

    public function map($condition): array
    {
        $gender = '';
        if ($condition->person_gender === 'male') {
            $gender = 'ذكر';
        } elseif ($condition->person_gender === 'female') {
            $gender = 'أنثى';
        } else {
            $gender = '-';
        }

        return [
            $condition->person_name,
            " " . ($condition->person_id_number ?? '-'),
            $gender,
            $condition->person_dob ? \Carbon\Carbon::parse($condition->person_dob)->format('Y-m-d') : '-',
            $condition->person_age ?? '-',
            $condition->person_phone ?? '-',
            $condition->person_original_address ?? '-',
            $condition->person_current_address ?? '-',
            $condition->condition_details,
        ];
    }
}
