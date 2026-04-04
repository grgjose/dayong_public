<?php

namespace App\Imports;

use DateTime;
use App\Models\ExcelMembers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;

class SalesImport implements ToModel
{
    use Importable;

    public $sheetName = '';

    public function __construct($sheetName)
    {
        $this->sheetName = $sheetName;
    }

    public function model (array $row)
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
            return new ExcelMembers([
                'timestamp' => $row[0],    
                'branch' => $row[1],
                'marketting_agent' => $row[2],
                'status' => $row[3],
                'phmember' => $row[4],
                'address' => $row[5],
                'civil_status' => $row[6],
                'birthdate' => $row[7],
                'age' => $row[8],
                'name' => $row[9],
                'contact_num' => $row[10],
                'type_of_transaction' => $row[11],
                'with_registration_fee' => $row[12],
                'registration_amount' => $row[13],
                'dayong_program' => $row[14],
                'application_no' => $row[15],
                'or_number' => $row[16],
                'or_date' => $row[17],
                'amount_collected' => $row[18],
                'name1' => $row[19],
                'age1' => $row[20],
                'relationship1' => $row[21],
                'name2' => $row[22],
                'age2' => $row[23],
                'relationship2' => $row[24],
                'name3' => $row[25],
                'age3' => $row[26],
                'relationship3' => $row[27],
                'name4' => $row[28],
                'age4' => $row[29],
                'relationship4' => $row[30],
                'sheetName' => $this->sheetName,
                'remarks' => '',
                'isImported' => false,
            ]);
        }
    }
}
