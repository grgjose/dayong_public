<?php

namespace App\Http\Controllers;

use DateTime;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Jobs\ImportExcelFile;
use App\Models\Beneficiary;
use App\Models\Member;
use App\Models\MembersProgram;
use App\Models\Claimant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use App\Imports\UsersImport;
use App\Imports\EntryImport;
use App\Imports\ExcelEntryImport;
use App\Models\Entry;
use App\Models\ExcelEntries;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;
use DateTimeZone;
use Exception;

class ExcelCollectionController extends Controller
{
    //
    public function index()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $excel_entries = DB::table('excel_entries')->orderBy('id')->get();
            $headers = array_keys((array) $excel_entries->first());

            return view('main', [
                'my_user' => $my_user,
                'excel_entries' => $excel_entries,
                'headers' => $headers,
            ])
            ->with('header_title', 'Excel Collection')
            ->with('subview', 'dashboard-contents.settings.excel-collection');

        } else {
            return redirect('/');
        }

    }

    public function retrieve(Request $request)
    {
        if(auth()->check()){
            
            return DataTables::query(DB::table('excel_entries')->where('isImported', false))->toJson();

        } else {
            return redirect('/');
        }   
    }

    public function store(Request $request)
    {
        if(auth()->check()){

            $validated = $request->validate([
                "branch_id" => ['required'],
                "marketting_agent" => ['nullable'],
                "member_id" => ['nullable'],
                "or_number" => ['nullable'],
                "amount" => ['nullable'],
                "number_of_payment" => ['nullable'],
                "program_id" => ['nullable'],
                "month_from" => ['nullable'],
                "month_to" => ['nullable'],
                "remarks" => ['nullable'],
            ]);

            //dd($validated["month_from"]);

            $contents = new Entry();

            $contents->branch_id = $validated["branch_id"];
            $contents->marketting_agent = $validated["marketting_agent"];
            $contents->member_id = $validated["member_id"];
            $contents->or_number = $validated["or_number"];
            $contents->amount = $validated["amount"];
            $contents->number_of_payment = $validated["number_of_payment"];
            $contents->program_id = $validated["program_id"];
            $contents->month_from = $validated["month_from"];
            $contents->month_to = $validated["month_to"];
            $contents->is_reactivated = 0;
            $contents->is_transferred = 0;
            $contents->remarks = $validated["remarks"];

            $contents->save();

            // Back to View
            return redirect('/entries')->with("success_msg", "Collection Added");

        } else {
            return redirect('/');
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->check()){
            $validated = $request->validate([
                'timestamp' => ['required'],
                'branch' => ['required'],
                'marketting_agent' => ['required'],
                'status' => ['required'],
                'phmember' => ['required'],
                'or_number' => ['required'],
                'or_date' => ['required'],
                'amount_collected' => ['required'],
                'month_of' => ['required'],
                'nop' => ['required'],
                'date_remitted' => ['required'],
                'dayong_program' => ['required'],
                'reactivation' => ['required'],
                'transferred' => ['required'],
            ]);

            $excel_entry = ExcelEntries::find($id);

            $toConvert = str_replace('T', ' ',$validated['timestamp']);
            $dateTimeObject = new DateTime($toConvert);  // Example DateTime object
            $excel_entry->timestamp = Date::dateTimeToExcel($dateTimeObject);  // Converts DateTime to Excel serial number

            $excel_entry->branch = $validated['branch'];
            $excel_entry->marketting_agent = $validated['marketting_agent'];
            $excel_entry->status = $validated['status'];
            $excel_entry->phmember = $validated['phmember'];
            $excel_entry->or_number = $validated['or_number'];

            $toConvert = str_replace('T', ' ',$validated['or_date']);
            $dateTimeObject = new DateTime($toConvert);  // Example DateTime object
            $excel_entry->or_date = Date::dateTimeToExcel($dateTimeObject);  // Converts DateTime to Excel serial number

            $excel_entry->amount_collected = $validated['amount_collected'];
            $excel_entry->month_of = $validated['month_of'];
            $excel_entry->nop = $validated['nop'];

            $toConvert = str_replace('T', ' ',$validated['date_remitted']);
            $dateTimeObject = new DateTime($toConvert);  // Example DateTime object
            $excel_entry->date_remitted = Date::dateTimeToExcel($dateTimeObject);  // Converts DateTime to Excel serial number

            $excel_entry->dayong_program = $validated['dayong_program'];
            $excel_entry->reactivation = $validated['reactivation'];
            $excel_entry->transferred = $validated['transferred'];

            $excel_entry->save();

            // Back to View
            return redirect('/excel-collection')->with("success_msg", "Data Updated");

        } else {
            return redirect('/');
        }
    }

    public function destroy(Request $request)
    {
        if(auth()->check()){

            // Destroy Request Data
            Entry::where("id", $request->input("id"))->delete();
            return redirect('/entries')->with("success_msg", "Deleted Successfully");

        } else {
            return redirect('/');
        }
    }
 
    public function importEntries()
    {
        set_time_limit(240);
        $programs = DB::table('programs')->orderBy('code')->get();
        $branches = DB::table('branches')->orderBy('branch')->get();
        $toImportEntries = DB::table('excel_entries')->orderBy('id')->skip(0)->take(1000)->get();

        foreach($toImportEntries as $toImport) {
            if($toImport->timestamp != ""){

                // Check if Marketting Agent in the User List
                // If not, Create Default User for Agent
                $name = ucwords(strtolower(trim($toImport->marketting_agent)), " .");
                if(strpos($name, ",") > 0){
                    $tmp = explode(",", $name);
                    $fname = ucwords($tmp[1]);
                    $lname = ucwords($tmp[0]);
                } else {
                    $fname = substr($name, strpos($name, " ") + 1);
                    $lname = substr($name, 0, strpos($name, " "));
                }
                
                $users = DB::table('users')->where('lname', $lname)
                ->where('fname', 'LIKE', $fname)->get();

                // Get Next Auto Increment
                $statement = DB::select("SHOW TABLE STATUS LIKE 'users'");
                $user_id = $statement[0]->Auto_increment;

                if(count($users) == 0){
                    // Save User Data
                    $newuser = new User();

                    $newuser->username = strtolower($lname);
                    $newuser->usertype = 3;
                    $newuser->fname = $fname;
                    $newuser->lname = $lname;
                    $newuser->profile_pic = "default.png";
                    $newuser->password = "password";

                    $newuser->save();
                } else {
                    $user_id = $users[0]->id;
                }
                
                // Save Entry Data (Member's Collection Information)
                $entry = new Entry();

                $name = ucwords(strtolower(trim($toImport->phmember)), " .");
                if(strpos($name, ",") > 0){
                    $tmp = explode(",", $name);
                    $fname = ucwords($tmp[1]);
                    $lname = ucwords($tmp[0]);
                } else {
                    $fname = substr($name, strpos($name, " ") + 1);
                    $lname = substr($name, 0, strpos($name, " "));
                }

                $members = DB::table('members')
                ->where('lname', $entry->lname)
                ->where('fname', 'LIKE', $entry->fname)
                ->get();

                //$anotherMembers = DB::table('members')->where('or_number', $toImport->or_number)->get();
                //$excel_members = DB::table('excel_members')->where('or_number', $toImport->or_number)->get();

                

                if(count($members) > 0){

                    if(is_numeric($toImport->timestamp)){
                        $timestamp = $this->excelTimestampToString((float)trim($toImport->timestamp));
                    } else {
                        break;
                    }
                    
                    $entry->created_at = $timestamp;

                    try{
                        $entry->save();
                    }
                    catch(Exception $e){
                        dd($e->getMessage());   // insert query
                    }

                    $branch_id = 0;
                    foreach($branches as $branch){
                        if(strtolower(trim($branch->branch)) == strtolower(trim($toImport->branch))){
                            $branch_id = $branch->id;
                            break; break;
                        }
                    }

                    $program_id = 0;
                    foreach($programs as $program){
                        if(strtolower(trim($program->code)) == strtolower(trim($toImport->dayong_program))){
                            $program_id = $program->id;
                            break; break;
                        }
                    }

                    $entry->branch_id = $branch_id;

                    $entry->marketting_agent = $user_id;
                    $entry->member_id = $members[0]->id;
                    $entry->or_number = $toImport->or_number;
                    $entry->amount = $toImport->amount_collected;
                    $entry->number_of_payment = $toImport->nop;
                    $entry->program_id = $program_id; 
                    $entry->is_reactivated = $toImport->reactivation == "Yes" ? 1 : 0;
                    $entry->is_transferred = $toImport->transferred == "Yes" ? 1 : 0;

                    $entry->save();

                    $toDelete = ExcelEntries::find($toImport->id);
                    $toDelete->delete();

                }                        
            }
        }

        // Back to View
        return redirect('/entries')->with("success_msg","Created Successfully"); 
    }

    public function upload(Request $request)
    {

        if(auth()->check()){

            set_time_limit(400);
            //ini_set('memory_limit', '2048M');

            $my_user = auth()->user();
            $validated = $request->validate([
                'upload_file' => ['required'],
                'sheetName' => ['required'],
            ]);

            $import = new ExcelEntryImport($validated['sheetName']);
            //Excel::toImport($import, $validated['upload_file']);
            (new ExcelEntryImport($validated['sheetName']))->import($validated['upload_file']);

            return redirect('/excel-new-sales')->with("success_msg", "Uploaded Successfully");

        } else {
            return redirect('/');
        }

    }

    public function parseFullName($fullName) 
    {
        // Define common name extensions
        $extensions = ['Jr.', 'Sr.', 'II', 'III', 'IV', 'V'];

        // Trim and clean up extra spaces
        $fullName = trim(preg_replace('/\s+/', ' ', $fullName));

        // Check if input is in "Last, First, Middle" or "Last, First Extension, Middle" format
        if (strpos($fullName, ',') !== false) {
            $parts = array_map('trim', explode(',', $fullName));

            $lastName = $parts[0] ?? '';
            $firstName = $parts[1] ?? '';
            $middleName = $parts[2] ?? '';

            // Split first name to check for an extension
            $firstNameParts = explode(' ', $firstName);
            if (count($firstNameParts) > 1 && in_array(end($firstNameParts), $extensions)) {
                $nameExtension = array_pop($firstNameParts);
                $firstName = implode(' ', $firstNameParts);
            } else {
                $nameExtension = '';
            }

            return [
                'fname' => ucwords(strtolower($firstName)),
                'mname' => ucwords(strtolower($middleName)),
                'lname' => ucwords(strtolower($lastName)),
                'ext' => ucwords(strtolower($nameExtension))
            ];
        }

        // Default format: "First Middle Last Extension"
        $parts = explode(' ', $fullName);
        $count = count($parts);

        $firstName = '';
        $middleName = '';
        $lastName = '';
        $nameExtension = '';

        if ($count == 1) {
            $firstName = $parts[0];
        } elseif ($count == 2) {
            $firstName = $parts[0];
            $lastName = $parts[1];
        } elseif ($count >= 3) {
            if (in_array($parts[$count - 1], $extensions)) {
                $nameExtension = $parts[$count - 1];
                array_pop($parts);
                $count--;
            }

            $firstName = $parts[0];
            $lastName = $parts[$count - 1];

            if ($count > 2) {
                $middleName = implode(' ', array_slice($parts, 1, $count - 2));
            }
        }

        return [
            'fname' => $firstName,
            'mname' => $middleName,
            'lname' => $lastName,
            'ext' => $nameExtension
        ];
    }

    public function loadSheets(Request $request)
    {
        if(auth()->check()){

            ini_set('memory_limit', '2048M');
            
  
            $validated = $request->validate(['upload_file' => ['required', 'file']]);
            //$spreadsheet = IOFactory::load($validated['upload_file']);
            $reader = IOFactory::createReaderForFile($validated['upload_file']);
            /** @var IReader $reader */
            $sheetNames = $reader->listWorksheetNames($validated['upload_file']); // No need to fully load the spreadsheet!

            return $sheetNames;

        } else {
            return redirect('/');
        }

    }

    public function viewDetails($id)
    {

        if(auth()->check()){
            $my_user = auth()->user();
            $entries = DB::table('excel_entries')->where('id', $id)->get();
            
            $entries[0]->timestamp = $this->excelToMySQLDateTime($entries[0]->timestamp);
            if($entries[0]->timestamp != null) { $entries[0]->timestamp = str_replace(" ", "T", $entries[0]->timestamp); }

            $entries[0]->date_remitted = $this->excelToMySQLDateTime($entries[0]->date_remitted);
            if($entries[0]->date_remitted != null) { $entries[0]->date_remitted = explode(' ', $entries[0]->date_remitted)[0]; }

            $entries[0]->or_date = $this->excelToMySQLDateTime($entries[0]->or_date);
            if($entries[0]->or_date != null) { $entries[0]->or_date = explode(' ', $entries[0]->or_date)[0]; }

            return view('dashboard-contents.settings.excel-collection-view', [
                'id' => $id,
                'entries' => $entries[0],
            ]);

        } else {
            return redirect('/');
        }
    }

    public function editDetails($id)
    {
        if(auth()->check()){
            $my_user = auth()->user();
            $entries = DB::table('excel_entries')->where('id', $id)->get();
            
            $entries[0]->timestamp = $this->excelToMySQLDateTime($entries[0]->timestamp);
            if($entries[0]->timestamp != null) { $entries[0]->timestamp = str_replace(" ", "T", $entries[0]->timestamp); }

            $entries[0]->date_remitted = $this->excelToMySQLDateTime($entries[0]->date_remitted);
            if($entries[0]->date_remitted != null) { $entries[0]->date_remitted = explode(' ', $entries[0]->date_remitted)[0]; }

            $entries[0]->or_date = $this->excelToMySQLDateTime($entries[0]->or_date);
            if($entries[0]->or_date != null) { $entries[0]->or_date = explode(' ', $entries[0]->or_date)[0]; }

            return view('dashboard-contents.settings.excel-collection-edit', [
                'id' => $id,
                'entries' => $entries[0],
            ]);

        } else {
            return redirect('/');
        }
    }

    public function excelToMySQLDateTime($excelDate)
    {
        try {
            if (!is_numeric($excelDate)) {
                return null;
            }
    
            $excelDate = floatval($excelDate);
    
            if ($excelDate < 0) {
                return null;
            }
    
            $days = floor($excelDate);
            $fraction = $excelDate - $days;
    
            $date = new DateTime('1899-12-30');
            $date->modify("+$days days");
    
            // Calculate time as hours, minutes, and seconds
            $hours = floor($fraction * 24);
            $minutes = floor(($fraction * 1440) % 60);
            $seconds = floor(($fraction * 86400) % 60);
    
            $date->setTime($hours, $minutes, $seconds);
    
            return $date->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            return null;
        }
    }

    /*
    public function mySQLDateTimeToExcel($mysqlDateTime)
    {
        // Validate the input format
        if (empty($mysqlDateTime) || !strtotime($mysqlDateTime)) {
            return null;  // Return null if the input is invalid
        }
    
        // Check if the input contains time (T separator)
        if (strpos($mysqlDateTime, 'T') !== false) {
            // Convert MySQL datetime string to Unix timestamp
            $timestamp = strtotime($mysqlDateTime);
        } else {
            // If no time, add a default time to the date (00:00:00) for calculation
            $mysqlDateTime .= 'T00:00:00';
            $timestamp = strtotime($mysqlDateTime);
        }
    
        // Excel's epoch start is 1900-01-01 00:00:00
        $excelEpoch = strtotime('1900-01-01 00:00:00');
    
        // Check for potential errors with strtotime
        if ($timestamp === false || $excelEpoch === false) {
            return null;  // Return null in case of error during strtotime
        }
    
        // Calculate the difference in seconds
        $diffInSeconds = $timestamp - $excelEpoch;
    
        // Convert to Excel date (days from 1900-01-01), divide by 86400 (seconds in a day)
        // Excel date system uses a "day" starting at 1900-01-01 00:00:00
        $excelDate = $diffInSeconds / 86400; // 86400 is the number of seconds in a day
    
        // Return the result with high precision (up to 9 decimal places)
        return round($excelDate, 9); 
    }
    */
    
    public function mySQLDateTimeToExcel($mysqlDateTime)
    {
        // Validate input format (expected format: YYYY-MM-DDTHH:MM:SS)
        if (empty($mysqlDateTime) || !preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $mysqlDateTime)) {
            return null;  // Return null if the input is invalid
        }
    
        // Split the input into date and time components
        list($date, $time) = explode('T', $mysqlDateTime);
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute, $second) = explode(':', $time);
    
        // Calculate the number of days since Excel epoch (1900-01-01)
        $daysSince1900 = $this->calculateDaysSince1900($year, $month, $day);
    
        // Calculate the time as a fraction of the day
        $timeAsFraction = ($hour * 3600 + $minute * 60 + $second) / 86400;
    
        // Combine the whole days and the fractional part
        $excelDate = $daysSince1900 + $timeAsFraction;
    
        // Return the result with high precision
        return round($excelDate, 9);
    }
    
    // Helper function to calculate the number of days since 1900-01-01
    private function calculateDaysSince1900($year, $month, $day)
    {
        $days = 0;
    
        // Account for the years from 1900 to the year before the given year
        for ($y = 1900; $y < $year; $y++) {
            $days += $this->isLeapYear($y) ? 366 : 365;
        }
    
        // Account for the months in the given year before the given month
        $daysInMonth = [31, 28 + $this->isLeapYear($year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        for ($m = 1; $m < $month; $m++) {
            $days += $daysInMonth[$m - 1];
        }
    
        // Add the days in the given month
        $days += $day - 1; // Subtract 1 because we start counting from the first day of the month
    
        return $days;
    }
    
    // Helper function to check if a year is a leap year
    private function isLeapYear($year)
    {
        return ($year % 4 == 0 && ($year % 100 != 0 || $year % 400 == 0));
    }
    

}
