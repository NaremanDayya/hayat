<?php

namespace App\Imports\Sheets;

use App\Models\FamilyMember;
use App\Imports\Sheets\FamiliesSheetImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ChildrenSheetImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    // Receives the families sheet object so we can read the map AFTER it has
    // been fully populated (Laravel Excel processes sheets sequentially).
    public function __construct(private FamiliesSheetImport $familiesSheet) {}

    public function startRow(): int
    {
        return 2; // skip header
    }

    public function model(array $row): ?FamilyMember
    {
        // Skip empty rows
        if (empty($row[0]) && empty($row[1])) {
            return null;
        }

        $rowKey   = (int) ($row[0] ?? 0);
        $familyId = $this->familiesSheet->familyMap[$rowKey] ?? null;

        if (!$familyId) {
            return null; // no matching family
        }

        $genderRaw = trim((string) ($row[4] ?? ''));
        $gender    = $genderRaw === 'أنثى' ? 'female' : 'male';

        return new FamilyMember([
            'family_id' => $familyId,
            'name'      => trim((string) ($row[1] ?? '')),
            'id_number' => $this->val($row[2]),
            'dob'       => $this->parseDate($row[3] ?? null),
            'gender'    => $gender,
        ]);
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
