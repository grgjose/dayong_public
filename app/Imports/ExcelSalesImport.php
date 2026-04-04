<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Importable;

class ExcelSalesImport implements WithMultipleSheets
{
    use Importable;

    public $sheetName = '';

    public function __construct($sheetName)
    {
        $this->sheetName = $sheetName;
    }

    public function sheets(): array
    {
        return [
            $this->sheetName => new SalesImport($this->sheetName),
        ];
    }
}

