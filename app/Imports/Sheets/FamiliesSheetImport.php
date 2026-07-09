<?php

namespace App\Imports\Sheets;

use App\Models\Family;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class FamiliesSheetImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    // Maps template row-key (column A value) => saved Family id
    public array $familyMap = [];

    public function startRow(): int
    {
        return 2; // skip header
    }

    public function model(array $row): ?Family
    {
        // Skip completely empty rows
        if (empty($row[0]) && empty($row[1]) && empty($row[6])) {
            return null;
        }

        $rowKey = (int) ($row[0] ?? 0);

        $family = Family::create([
            'husband_name'         => $this->val($row[1]),
            'husband_id_number'    => $this->val($row[2]),
            'husband_dob'          => $this->parseDate($row[3] ?? null),
            'husband_phone'        => $this->val($row[4]),
            'marital_status'       => $this->val($row[5]) ?: 'متزوج',
            'wife_name'            => $this->val($row[6]),
            'wife_id_number'       => $this->val($row[7]),
            'wife_dob'             => $this->parseDate($row[8] ?? null),
            'wife_phone'           => $this->val($row[9]),
            'original_address'     => $this->val($row[10]),
            'current_address'      => $this->val($row[11]) ?: 'النصيرات-مخيم حياة النويري',
            'family_members_count' => 0,
        ]);

        if ($rowKey > 0) {
            $this->familyMap[$rowKey] = $family->id;
        }

        return null; // already persisted via create()
    }

    private function val($value): ?string
    {
        $v = trim((string) ($value ?? ''));
        return $v !== '' ? $v : null;
    }

    private function parseDate($value): ?string
    {
        if (empty($value)) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
