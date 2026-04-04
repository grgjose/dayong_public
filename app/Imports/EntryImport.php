<?php

namespace App\Imports;

use DateTime;
use App\Models\ExcelEntries;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use PHPUnit\Exception;

class EntryImport implements ToModel
{
    use Importable;

    public $sheetName = '';

    public function __construct($sheetName)
    {
        $this->sheetName = $sheetName;
    }

    public function model(array $row)
    {
        if(trim(strtolower($row[0])) == "timestamp")
        {
            return null;
        }
        elseif(trim($row[0]) == "" || $row[0] == null)
        {
            return null;
        }
        else
        {
            return new ExcelEntries([
                'timestamp' => $row[0],
                'branch' => $row[1],
                'marketting_agent' => $row[2],
                'status' => $row[3],
                'phmember' => $row[4],
                'or_number' => $row[5],
                'or_date' => $row[6],
                'amount_collected' => $row[7],
                'month_of' => $row[8],
                'nop' => $row[9],
                'date_remitted' => $row[10],
                'dayong_program' => $row[11],
                'reactivation' => $row[12],
                'transferred' => $row[13],
                'sheetName' => $this->sheetName,
                'remarks' => '',
                'isImported' => false,
            ]);
        }
    }

}
