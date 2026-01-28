<?php

namespace App\Imports;

use App\Models\Family;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FamiliesImport implements ToModel, WithStartRow
{
    /**
     * Start from the second row (skip header)
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Check if the row is mostly empty
        if (!isset($row[0]) && !isset($row[3])) {
            return null;
        }

        return new Family([
            'husband_name'         => $row[0] ?? null,
            'husband_id_number'    => $row[1] ?? null,
            'marital_status'       => $row[2] ?? 'متزوج',
            'wife_name'            => $row[3] ?? null,
            'wife_id_number'       => $row[4] ?? null,
            'husband_phone'        => $row[5] ?? null,
            'wife_dob'             => $this->transformDate($row[6] ?? null),
            'family_members_count' => $row[7] ?? 0,
            'original_address'     => $row[8] ?? null,
            'current_address'      => $row[9] ?? null,
            'wife_phone'           => $row[10] ?? null,
        ]);
    }

    /**
     * Helper to handle excel date formats safely
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;

        try {
            // If it's a numeric value from Excel (date serial)
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            
            // Otherwise try to parse it
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
