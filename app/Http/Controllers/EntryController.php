<?php

namespace App\Http\Controllers;

use DateTime;
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
use App\Models\Entry;
use App\Models\ExcelEntries;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Exception;

class EntryController extends Controller
{
    //
    public function index()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $members = DB::table('members')->orderBy('id')->get();
            $programs = DB::table('programs')->orderBy('id')->get();
            $branches = DB::table('branches')->orderBy('id')->get();
            $entries = DB::table('entries')->orderBy('created_at', 'desc')->get();
            $users = DB::table('users')->orderBy('id')->get();

            return view('main', [
                'my_user' => $my_user,
                'members' => $members,
                'programs' => $programs,
                'branches' => $branches,
                'entries' => $entries,
                'users' => $users
            ])
            ->with('header_title', 'Collection')
            ->with('subview', 'dashboard-contents.modules.data-entry');

        } else {
            return redirect('/');
        }
    }

    public function store(Request $request)
    {
        if(auth()->check()){

            $validated = $request->validate([
                "branch_id" => ['required'],
                "agent_id" => ['nullable'],
                "member_id" => ['nullable'],
                "or_number" => ['nullable'],
                "amount" => ['nullable'],
                "number_of_payment" => ['required'],
                "program_id" => ['nullable'],
                "month_from" => ['required'],
                "month_to" => ['required'],
                "incentives" => ['nullable'],
                "reactivated" => ['nullable'],
                "transferred" => ['nullable'],
                "remarks" => ['nullable'],
            ]);
            
            $my_user = auth()->user();

            // $fids = DB::table('fidelity')->where('user_id', $validated["agent_id"])->get();
            // $fid = false;
            // if(count($fids) == 0){ $fid = false; }
            // else { $fid = true; }

            $contents = new Entry();

            $contents->branch_id = $validated["branch_id"];
            $contents->encoder_id = $my_user->id;
            $contents->agent_id = $validated["agent_id"];
            $contents->member_id = $validated["member_id"];
            $contents->or_number = $validated["or_number"];
            $contents->amount = $validated["amount"];
            $contents->number_of_payment = $validated["number_of_payment"];
            $contents->program_id = $validated["program_id"];
            $contents->month_from = $validated["month_from"];
            $contents->month_to = $validated["month_to"];
            $contents->incentives = $validated["incentives"];

            // if($fid == true) {
            //     $contents->incentives_total = $validated["amount"] * ($validated["incentives"] / 100);
            //     $contents->incentives_total = $contents->incentives_total - ($contents->incentives_total * 0.1);
            //     $contents->net = $validated["amount"] - $contents->incentives_total;
            //     $contents->fidelity = ($contents->incentives_total * 0.1);
            // } else {
            //     $contents->incentives_total = $validated["amount"] * ($validated["incentives"] / 100);
            //     $contents->net = $validated["amount"] - $contents->incentives_total;
            //     $contents->fidelity = 0;
            // }

            $contents->is_reactivated = 0;
            if(isset($validated["reactivated"])){ $contents->is_reactivated = 1; }

            $contents->is_transferred = 0;
            if(isset($validated["transferred"])){ $contents->is_transferred = 1; }

            $contents->is_remitted = false;
            $contents->remarks = $validated["remarks"];

            $contents->save();

            // Back to View
            return redirect('/entries')->with("success_msg", "Collection Added");

        } else {
            return redirect('/');
        }
    }

    public function update(Request $request)
    {
        //code
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
 
    public function import(Request $request)
    {
        $my_user = auth()->user(); $total_count = 0;
        $programs = DB::table('programs')->orderBy('code')->get();
        $branches = DB::table('branches')->orderBy('branch')->get();
        $toImportEntries = DB::table('excel_entries')
        ->where('isImported', false)->orderBy('id')
        ->skip(0)->take((int)$request->input('data_count'))->get();

        foreach($toImportEntries as $toImport) {

            $total_count = $total_count + 1;
            if($toImport->timestamp != ""){

                // (PERFORM VALIDATIONS)

                    // 1. Validate Timestamp
                        $timestamp = $this->excelToMySQLDateTime(trim($toImport->timestamp));
                        if($timestamp == null) { 
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Invalid Timestamp!";
                            $excelEntry->save();
                            goto next; 
                        }
                    //

                    // 2. Validate Branch

                        $branches = DB::table('branches')->where('branch', trim($toImport->branch))->get();

                        if(count($branches) == 0){ 
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Branch is not in the Branches List";
                            $excelEntry->save();
                            goto next;
                        } elseif (count($branches) > 1){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "It matched with More than 2 Branches on the User List, please be specific";
                            $excelEntry->save();
                            goto next;
                        }

                        // Get ID of Branch (For Member)
                        $branch_id = $branches[0]->id;

                    //

                    // 3. Validate Marketting Agent

                        // If not Existing in User List, Add Remarks to the ID
                        $name = ucwords(strtolower(trim($toImport->marketting_agent)), " .");
                        if(strpos($name, ",") > 0){
                            $tmp = explode(",", $name);
                            $fname = ucwords(explode(".", $tmp[1])[0]);
                            $lname = ucwords($tmp[0]);
                        } else {
                            $fname = substr($name, strpos($name, " ") + 1);
                            $lname = substr($name, 0, strpos($name, " "));
                        }
                        
                        $users = DB::table('users')->where('lname', $lname)->get();

                        if(count($users) == 0){ 
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Marketting Agent is not in the Users List";
                            $excelEntry->save();
                            goto next;
                        } elseif (count($users) > 1){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "It matched with More than 2 Marketting Agent on the User List, please be specific";
                            $excelEntry->save();
                            goto next;
                        }

                        $user_id = $users[0]->id;

                    //

                    // 4. Validate Status
                        if((strtolower(trim($toImport->status)) != "active") 
                         && strtolower(trim($toImport->status)) != "collector"){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Status should be Active or Collector";
                            $excelEntry->save();
                            goto next;
                        }
                        $status = trim($toImport->status);
                    //

                    // 5. Validate PH/Member
                    
                        // Get Next Auto Increment
                        $statement = DB::select("SHOW TABLE STATUS LIKE 'members'");
                        $member_id = $statement[0]->Auto_increment;
                        
                        $fullname = $this->parseFullName(trim($toImport->phmember));

                        $members = DB::table('members')
                        ->where('lname', $fullname['lname'])
                        ->where('fname', $fullname['fname'])
                        ->where('mname', $fullname['mname'])
                        ->where('ext', $fullname['ext'])
                        ->get();

                        if(count($members) == 0){ 
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Member is not existing in Member's List";
                            $excelEntry->save();
                            goto next;
                        } elseif (count($members) > 1){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "It matched with More than 2 Members on the Member List, please be specific";
                            $excelEntry->save();
                            goto next;
                        }

                        $member_id = $members[0]->id;

                    //

                    // 6. Validate OR Number
                        $query = DB::table('members_program')->where('or_number', '=', trim($toImport->or_number))->get();

                        if (trim($toImport->or_number) == ""){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Missing OR Number!";
                            $excelEntry->save();
                            goto next;
                        } elseif (count($query) > 0){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "OR Number is already existing!";
                            $excelEntry->save();
                            goto next;
                        } elseif (!is_numeric(trim($toImport->or_number))){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "OR Number is not a Number!";
                            $excelEntry->save();
                            goto next;
                        } 
                        $or_number = (int)trim($toImport->or_number);
                    //

                    // 7. Validate OR Date
                        $or_date = $this->excelToMySQLDateTime(trim($toImport->or_date));

                        if($or_date == null){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Invalid OR Date Value!";
                            $excelEntry->save();
                            goto next;
                        }
                    //

                    // 8. Validate Amount Collected
                        if (trim($toImport->amount_collected) == ""){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Missing Amount Collected!";
                            $excelEntry->save();
                            goto next;
                        } elseif (!is_numeric(trim($toImport->amount_collected))){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Amount is not a Number!";
                            $excelEntry->save();
                            goto next;
                        } 
                        $amount = (int)trim($toImport->amount_collected);
                    //

                    // 9. Validate Month Of (NOP)
                        $month_of = trim($toImport->month_of);

                        if($month_of == ""){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Month Of is Empty!";
                            $excelEntry->save();
                            goto next;
                        } else {

                            $months = explode(",", $month_of);
                            
                            for($i = 0; $i <= count($months) - 1; $i++){
                                $month = strtolower(trim($months[$i]));
                                
                                if(!$this->validateMonth($month)){
                                    $excelEntry = ExcelEntries::find($toImport->id);
                                    $excelEntry->remarks = "Invalid Month Value" . $month . "!";
                                    $excelEntry->save();
                                    goto next;
                                } 
                            }

                            $nop = count($months);
                            $m1 = $this->getMonthNumber($months[0]);
                            $m2 = $this->getMonthNumber($months[count($months) - 1]);
                            
                        }

                    //

                    // 10. Validate Date Remitted
                        $date_remitted = $this->excelToMySQLDateTime(trim($toImport->date_remitted));

                        if($date_remitted == null){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Invalid Date Remitted Value!";
                            $excelEntry->save();
                            goto next;
                        }
                        
                    //

                    // 11. Validate Dayong Program
                        $programs = DB::table('programs')
                        ->where('code', '=', trim($toImport->dayong_program))->get();

                        if(count($programs) == 0){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "Program not Existing in Settings!";
                            $excelEntry->save();
                            goto next;
                        } elseif (count($programs) > 1){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "More than 2 Programs found in Settings!";
                            $excelEntry->save();
                            goto next;
                        } else {
                            $program_id = $programs[0]->id;
                        }

                    //

                    // 12. Validate Type of Transaction
                        if((strtolower(trim($toImport->reactivation)) != "no") 
                         && strtolower(trim($toImport->reactivation)) != "yes"){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "reactivation column should only be No or Yes!";
                            $excelEntry->save();
                            goto next;
                        }

                        if((strtolower(trim($toImport->transferred)) != "no") 
                         && strtolower(trim($toImport->transferred)) != "yes"){
                            $excelEntry = ExcelEntries::find($toImport->id);
                            $excelEntry->remarks = "transferred column should only be No or Yes!";
                            $excelEntry->save();
                            goto next;
                        }

                        $is_reactivated = (strtolower(trim($toImport->reactivation)) == "yes")? true: false;
                        $is_transferred = (strtolower(trim($toImport->transferred)) == "yes")? true: false;
                    //

                //
                
                // IMPORT TO COLLECTION TABLE

                    $entry = new Entry();
                    $entry->branch_id = $branch_id;
                    $entry->encoder_id = $my_user->id;
                    $entry->agent_id = $user_id;
                    $entry->branch_id = $branch_id;
                    $entry->member_id = $member_id;
                    $entry->or_number = $or_number;
                    $entry->or_date = $or_date;
                    $entry->amount = $amount;
                    $entry->number_of_payment = $nop;
                    $entry->date_remitted = $date_remitted;
                    $entry->program_id = $program_id;
                    $entry->month_from = $m1;
                    $entry->month_to = $m2;
                    $entry->is_reactivated = $is_reactivated;
                    $entry->is_transferred = $is_transferred;
                    $entry->created_at = $timestamp;
                    $entry->save();

                    $excelEntry = ExcelEntries::find($toImport->id);
                    $excelEntry->remarks = "";
                    $excelEntry->isImported = true;
                    $excelEntry->save();


                //
            }

            next:

        }

        // Back to View
        return redirect('/entries')->with("success_msg",$total_count. " Collection Records Imported Successfully"); 
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
            'fname' => ucwords(strtolower($firstName)),
            'mname' => ucwords(strtolower($middleName)),
            'lname' => ucwords(strtolower($lastName)),
            'ext' => ucwords(strtolower($nameExtension))
        ];
    }

    public function viewDetails($id)
    {

        if(auth()->check()){
            $my_user = auth()->user();
            $entries = DB::table('entries')->where('id', $id)->get();
            $agent = DB::table('users')->where('id', $entries[0]->agent_id)->get();
            $members = DB::table('members')->where('id', $entries[0]->member_id)->get();
            $branches = DB::table('branches')->where('id', $entries[0]->branch_id)->get();
            $programs = DB::table('programs')->where('id', $entries[0]->program_id)->get();
            
            return view('dashboard-contents.modules.data-entry-view', [
                'id' => $id,
                'entries' => $entries[0],
                'agent' => $agent[0],
                'members' => $members[0],
                'branches' => $branches[0],
                'programs' => $programs[0],
            ]);

        } else {
            return redirect('/');
        }
    }

    public function editDetails($id)
    {

        if(auth()->check()){
            $my_user = auth()->user();
            $entries = DB::table('entries')->where('id', $id)->get();
            $agent = DB::table('users')->where('id', $entries[0]->agent_id)->get();
            $member = DB::table('members')->where('id', $entries[0]->member_id)->get();
            $branch = DB::table('branches')->where('id', $entries[0]->branch_id)->get();
            $program = DB::table('programs')->where('id', $entries[0]->program_id)->get();

            $users = DB::table('users')->get();
            $branches = DB::table('branches')->where('id', $entries[0]->branch_id)->get();
            $programs = DB::table('programs')->where('id', $entries[0]->program_id)->get();

            return view('dashboard-contents.modules.data-entry-edit', [
                'id' => $id,
                'entries' => $entries[0],
                'agent' => $agent[0],
                'member' => $member[0],
                'branch' => $branch[0],
                'program' => $program[0],
                'branches' => $branches[0],
                'programs' => $programs[0],
                'users' => $users
            ]);

        } else {
            return redirect('/');
        }
    }

    public function excelTimestampToString($excelTimestamp)
    {
        // Define the base date for Excel dates (January 1, 1900 is considered day 1 in Excel)
        $excelEpoch = new DateTime('1899-12-30'); // Excel date 0 corresponds to 1899-12-30
        
        // Separate the integer part (days) and fractional part (time)
        $days = floor($excelTimestamp);
        $fractionalDay = $excelTimestamp - $days;
        
        // Add the days to the epoch
        $excelEpoch->modify("+{$days} days");
        
        // Calculate the time from the fractional day
        $totalSeconds = round($fractionalDay * 86400); // 86400 seconds in a day
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        
        // Add the time to the date
        $excelEpoch->setTime($hours, $minutes, $seconds);
        
        // Return the formatted date string
        return $excelEpoch->format('Y-m-d H:i:s');
    }

    public function getIncentivesMatrix($id, $program_id)
    {
        $matrix = DB::table('matrix')->where('program_id', $program_id)->orderBy('program_id')->get();
        $entries = DB::table('entries')
            ->where('member_id', $id)
            ->where('program_id', $program_id)
            ->where('remarks', "!=", "REGISTRATION")
            ->get();
        $nop = count($entries) + 1;

        if(count($matrix) == 0){ return ""; }

        foreach($matrix as $m){
            $arr = explode('-', $m->nop);
            if($arr[0] != 'up' && $arr[1] != 'up'){
                if((int)$arr[0] <= (int)$nop && (int)$arr[1] >= (int)$nop){
                    return $m->percentage;
                }
            } else {
                if((int)$arr[0] >= $nop){
                    return $m->percentage;
                }
            }
        }

        return "";
    }

    public function excelDateToPhpDate($excelDate) 
    {
        // Ensure it's a float
        $excelDate = (float) $excelDate;
    
        // Convert Excel date to Unix timestamp
        $unixTimestamp = ($excelDate - 25569) * 86400;
    
        // Format the date as M/D/YYYY H:i:s AM/PM
        return date("n/j/Y g:i:s A", $unixTimestamp);
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

    public function validateMonth($month)
    {
        $months = [
            'january', 'february', 'march', 'april', 'may', 'june',
            'july', 'august', 'september', 'october', 'november', 'december',
            'jan', 'feb', 'mar', 'apr', 'may', 'jun',
            'jul', 'aug', 'sep', 'oct', 'nov', 'dec'
        ];
    
        return in_array(strtolower($month), $months, true);
    }

    public function getMonthNumber($month)
    {
        $months = [
            'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4, 'may' => 5, 'june' => 6,
            'july' => 7, 'august' => 8, 'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12,
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6,
            'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
        ];
    
        $month = strtolower($month);
    
        return $months[$month] ?? false;
    }

    public function getMemberPrograms($memberId)
    {
        $programs = DB::table('members_program')
            ->join('programs', 'members_program.program_id', '=', 'programs.id')
            ->where('members_program.member_id', $memberId)
            ->select('programs.id', 'programs.code', 'programs.amount_min')
            ->get();

        return response()->json($programs);
    }

}
