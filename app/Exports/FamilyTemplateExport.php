<?php

namespace App\Exports;

use App\Exports\Sheets\FamiliesTemplateSheet;
use App\Exports\Sheets\ChildrenTemplateSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FamilyTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new FamiliesTemplateSheet(),
            new ChildrenTemplateSheet(),
        ];
    }
}
