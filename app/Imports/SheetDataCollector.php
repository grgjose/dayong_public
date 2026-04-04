<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SheetDataCollector implements ToCollection, WithHeadingRow
{
    protected $storage;
    protected string $sheetName;

    public function __construct(array &$storage, string $sheetName)
    {
        $this->storage = &$storage; // Assign reference here
        $this->sheetName = $sheetName;
    }

    public function collection(Collection $rows)
    {
        $this->storage[$this->sheetName] = $rows->toArray();
    }

    public function startRow(): int
    {
        return 2;
    }
}