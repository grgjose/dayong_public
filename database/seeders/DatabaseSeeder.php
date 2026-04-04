<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Usertype;
use App\Models\Branch;
use App\Models\Claimant;
use App\Models\Beneficiary;
use App\Models\Program;
use App\Models\Matrix;
use App\Models\Member;
use App\Imports\DatabaseImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Shared\Date;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Build full path to the file
        $path = storage_path('app/public/imports/DatabaseSeeder.xlsx');
        
        // Declare Importer
        $importer = new DatabaseImport();
        Excel::import($importer, $path);
        
        // Get Data from Importer
        $data = $importer->getData();
        
        // Segregate by Sheets
        $usertypes = $data['Usertypes'];
        $users = $data['Users'];
        $branches = $data['Branches'];
        $programs = $data['Programs'];
        $matrix = $data['Matrix'];

        $usertypes_arr = array(); $i = 1;

        //Import Usertypes
        foreach(array_slice($usertypes, 1) as $row){
            if($row[1] != null || $row[1] != ""){
                Usertype::factory()->create([
                    'usertype' => $row[1],
                ]);
    
                $usertypes_arr[$row[1]] = $i; $i++;
            }
        }

        //Import Users
        foreach(array_slice($users, 1) as $row){
            if($row[1] != "" && $row[1] != null){
                User::factory()->create([
                    'username' => $row[1],
                    'usertype' => $usertypes_arr[$row[2]],
                    'fname' => $row[3],
                    'mname' => $row[4],
                    'lname' => $row[5],
                    'ext' => $row[6],
                    'email' => $row[7],
                    'contact_num' => $row[8],
                    'address' => $row[9],
                    'birthdate' => is_numeric($row[10]) ? Date::excelToDateTimeObject($row[10])->format('Y-m-d') : null,
                    'password' => Hash::make($row[11]),
                    'status' => $row[12],
                ]);
            }
        }

        //Import Branches
        foreach(array_slice($branches, 1) as $row){
            if($row[3] != ""){
                
                Branch::factory()->create([
                    'code' => $row[1],
                    'city' => $row[2],
                    'branch' => $row[3],
                    'address' => $row[4],
                    'description' => $row[5],
                ]);
            }
        }

        // Import Programs
        foreach(array_slice($programs, 1) as $row){
            if($row[1] != ""){

                Program::factory()->create([
                    'code' => $row[1],
                    'description' => $row[2],
                    'beneficiaries_count' => $row[3],
                    'age_min' => $row[4],
                    'age_max' => $row[5],
                    'ben_age_min' => $row[6],
                    'ben_age_max' => $row[7],
                    'term_min' => $row[8],
                    'term_max' => $row[9],
                    'amount_min' => $row[10],
                    'amount_max' => $row[11],
                    'status' => $row[12],
                ]); 
            }
        }

    }
}
