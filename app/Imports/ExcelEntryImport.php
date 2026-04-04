<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Importable;

class ExcelEntryImport implements WithMultipleSheets
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public $sheetName = 0;

    public function __construct($sheetName)
    {
        $this->sheetName = $sheetName;
    }

    public function sheets(): array
    {
        return [
            $this->sheetName => new EntryImport($this->sheetName),
        ];
    }
}
