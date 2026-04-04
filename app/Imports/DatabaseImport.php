<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DatabaseImport implements WithMultipleSheets
{
    protected array $data = [];

    public function sheets(): array
    {
        // You may need to dynamically define this if sheet names vary
        $sheetNames = ['Usertypes', 'Users', 'Branches', 'Programs', 'Matrix']; // or use Sheet index: 0, 1

        $sheets = [];
        foreach ($sheetNames as $name) {
            $sheets[$name] = new SheetDataCollector($this->data, $name);
        }

        return $sheets;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
