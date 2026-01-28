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
        // Excel columns mapping:
        // 0: اسم الزوج (Husband Name)
        // 1: رقم هوية الزوج (Husband ID Number)
        // 2: رقم هاتف الزوج (Husband Phone)
        // 3: الحالة الاجتماعية (Marital Status)
        // 4: اسم الزوجة (Wife Name)
        // 5: رقم هوية الزوجة (Wife ID Number)
        // 6: عدد أفراد الأسرة (Family Members Count)
        // 7: مكان السكن الأصلي (Original Address)
        
        // Check if the row is mostly empty
        if (empty($row[0]) && empty($row[4])) {
            return null;
        }

        // Handle current address - set default if not provided
        $currentAddress = !empty($row[8]) ? $row[8] : 'النصيرات-مخيم حياة النويري';

        return new Family([
            'husband_name'         => !empty($row[0]) ? $row[0] : null,
            'husband_id_number'    => !empty($row[1]) ? $row[1] : null,
            'husband_phone'        => !empty($row[2]) ? $row[2] : null,
            'marital_status'       => !empty($row[3]) ? $row[3] : 'متزوج',
            'wife_name'            => !empty($row[4]) ? $row[4] : null,
            'wife_id_number'       => !empty($row[5]) ? $row[5] : null,
            'family_members_count' => !empty($row[6]) ? (int)$row[6] : 0,
            'original_address'     => !empty($row[7]) ? $row[7] : null,
            'current_address'      => $currentAddress,
            'husband_dob'          => null,
            'wife_dob'             => null,
            'wife_phone'           => null,
        ]);
    }
}
