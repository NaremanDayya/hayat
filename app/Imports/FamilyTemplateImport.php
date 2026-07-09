<?php

namespace App\Imports;

use App\Imports\Sheets\FamiliesSheetImport;
use App\Imports\Sheets\ChildrenSheetImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FamilyTemplateImport implements WithMultipleSheets
{
    private FamiliesSheetImport $familiesSheet;

    public function __construct()
    {
        $this->familiesSheet = new FamiliesSheetImport();
    }

    public function sheets(): array
    {
        return [
            0 => $this->familiesSheet,
            1 => new ChildrenSheetImport($this->familiesSheet),
        ];
    }

    // Called after sheet 0 is fully processed so the map is populated
    // before sheet 1 starts. Laravel Excel processes sheets sequentially.
}
