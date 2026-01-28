<?php

namespace App\Imports;

use App\Models\Family;
use App\Models\FamilyMember;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class FamilyMembersImport implements ToModel, WithStartRow
{
    protected $gender;

    public function __construct($gender = 'male')
    {
        $this->gender = $gender;
    }

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
        // row[0]: Full name (3 sections: [First] [Father] [Grandfather])
        // row[1]: ID Number
        // row[2]: Date of Birth
        
        if (!isset($row[0]) || empty($row[0])) {
            return null;
        }

        $fullName = trim($row[0]);
        $nameParts = explode(' ', $fullName);
        
        $firstName = $nameParts[0] ?? '';
        $fatherPart = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '';

        // Try to find the family by father's name
        $family = null;
        if ($fatherPart) {
            $family = Family::where('husband_name', 'like', '%' . $fatherPart . '%')->first();
        }

        return new FamilyMember([
            'family_id' => $family ? $family->id : null,
            'name'      => $fullName,
            'id_number' => $row[1] ?? null,
            'dob'       => $this->transformDate($row[2] ?? null),
            'gender'    => $this->gender,
        ]);
    }

    /**
     * Helper to handle excel date formats safely
     */
    private function transformDate($value)
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
