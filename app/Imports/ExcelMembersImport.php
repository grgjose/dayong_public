<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\Importable;

class ExcelMembersImport implements WithMultipleSheets
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
            $this->sheetName => new MembersImport(),
        ];
    }
}
