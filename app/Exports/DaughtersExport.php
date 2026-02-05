<?php

namespace App\Exports;

use App\Models\FamilyMember;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DaughtersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = FamilyMember::where('gender', 'female')->with('family');

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('id_number', 'like', '%' . $search . '%');
            });
        }

        if (!empty($this->filters['minAge']) || !empty($this->filters['maxAge'])) {
            $minAge = $this->filters['minAge'] ?? null;
            $maxAge = $this->filters['maxAge'] ?? null;
            
            $dateEnd = $minAge ? now()->subYears($minAge)->endOfDay()->format('Y-m-d') : now()->format('Y-m-d');
            $dateStart = $maxAge ? now()->subYears($maxAge)->startOfDay()->format('Y-m-d') : '1900-01-01';
            
            $query->whereBetween('dob', [$dateStart, $dateEnd]);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'رقم الهوية',
            'تاريخ الميلاد',
            'اسم الأب',
            'رقم هوية الأب',
            'رقم جوال الأب',
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
            $member->family->husband_phone ?? '',
        ];
    }
}
