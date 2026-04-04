<?php

namespace App\Http\Controllers;

use App\Events\ProcessExcelFile;
use DateTime;
use App\Jobs\ImportExcelFile;
use App\Imports\SalesImport;
use App\Imports\ExcelImport;
use App\Models\Entry;
use App\Models\Member;
use App\Models\MembersProgram;
use App\Models\User;
use App\Models\Claimant;
use App\Models\Beneficiary;
use App\Models\ExcelMembers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PHPUnit\Logging\Exception;
use TypeError;

//List of Constants (NS)

    const TIMESTAMP = 0;
    const BRANCH = 1;
    const MARKETTING_AGENT = 2;
    const STATUS = 3;
    const PHMEMBER = 4;
    const ADDRESS = 5;
    const CIVIL_STATUS = 6;
    const BIRTHDATE = 7;
    const AGE = 8;
    const NAME = 9;
    const CONTACT_NO = 10;
    const TYPE_OF_TRANSACTION = 11;
    const WITH_REGISTRATION_FEE = 12;
    const REGISTRATION_AMOUNT = 13;
    const DAYONG_PROGRAM = 14;
    const APPLICATION_NO = 15;
    const OR_NUMBER = 16;
    const OR_DATE = 17;
    const AMOUNT_COLLECTED = 18;

    const NAME1 = 19;
    const AGE1 = 20;
    const RELATIONSHIP1 = 21;

    const NAME2 = 22;
    const AGE2 = 23;
    const RELATIONSHIP2 = 24;

    const NAME3 = 25;
    const AGE3 = 26;
    const RELATIONSHIP3 = 27;

    const NAME4 = 28;
    const AGE4 = 29;
    const RELATIONSHIP4 = 30;

//

class NewSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $users = DB::table('users')->orderBy('usertype', 'asc')->get();
            $members = DB::table('members')->orderBy('created_at', 'desc')->where('is_deleted', false)->get();
            $members_program = DB::table('members_program')->where('is_deleted', false)->get();
            $programs = DB::table('programs')->orderBy('code')->get();
            $branches = DB::table('branches')->orderBy('branch')->get();

            return view('main', [
                'my_user' => $my_user,
                'members' => $members,
                'members_program' => $members_program,
                'programs' => $programs,
                'branches' => $branches,
                'users' => $users,
            ])
            ->with('header_title', 'New Sales')
            ->with('subview', 'dashboard-contents.modules.new-sales');

        } else {
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if(auth()->check()){

            // Get Request Data
            $my_user = auth()->user();
            $validated = $request->validate([

                // Location and Program
                "program_id" => ['required'],
                "branch_id" => ['required'],
                "or_number" => ['required'],
                "app_no" => ['required'],
                "member_id" => ['required'],
                "created_at" => ['required'],
                
                // Others
                "contact_person" => ['required'],
                "contact_person_num" => ['required'],
                "registration_fee" => ['nullable'],
                "agent_id" => ['nullable'],
                "amount" => ['nullable'],
                "incentives" => ['nullable'],
                "fidelity" => ['nullable'],

            ]);

            // Save Request Data (Members_Program)
            $memberProgram = new MembersProgram();
            $memberProgram->app_no = $validated['app_no'];
            $memberProgram->user_id = $my_user->id;
            $memberProgram->member_id = $validated['member_id'];
            $memberProgram->program_id = $validated['program_id'];
            $memberProgram->branch_id = $validated['branch_id'];
            $memberProgram->or_number = $validated['or_number'];
            $memberProgram->registration_fee = $validated['registration_fee'];
            $memberProgram->agent_id = $validated['agent_id'];
            $memberProgram->amount = $validated['amount'];
            $memberProgram->incentives = $validated['incentives'];
            $memberProgram->incentives_total = $memberProgram->amount - ($memberProgram->amount * ($memberProgram->incentives / 100));
            $memberProgram->fidelity = $validated['fidelity'];
            $memberProgram->fidelity_total = $memberProgram->incentives_total * ($memberProgram->fidelity / 100);
            $memberProgram->incentives_total = $memberProgram->incentives_total - $memberProgram->fidelity_total;
            $memberProgram->net = $memberProgram->amount  - $memberProgram->incentives_total - $memberProgram->fidelity_total;
            $memberProgram->contact_person = $validated['contact_person'];
            $memberProgram->contact_person_num = $validated['contact_person_num'];
            $memberProgram->status = "active";

            $memberProgram->save();

            // Back to View
            return redirect('/new-sales')->with("success_msg", "New Sales Created Successfully");

        } else {
            return redirect('/');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->request([
            'branch_id' => ['required'],
            'program_id' => ['required'],
        ]);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Imports from Excel New Sales Settings to New Sales
     */
    public function import(Request $request)
    {
        $my_user = auth()->user();
        $programs = DB::table('programs')->orderBy('code')->get();
        $branches = DB::table('branches')->orderBy('branch')->get();
        $toImportMembers = DB::table('excel_members')
        ->where('isImported', false)->orderBy('id')
        ->skip(0)->take((int)$request->input('data_count'))->get();

        foreach($toImportMembers as $toImport) {

            if($toImport->timestamp != ""){

                // (PERFORM VALIDATIONS)

                    // 1. Validate Timestamp
                        $timestamp = $this->excelToMySQLDateTime(trim($toImport->timestamp));
                        if($timestamp == null) { 
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Invalid Timestamp!";
                            $excelMember->save();
                            continue; 
                        }
                    //

                    // 2. Validate Branch

                        $branches = DB::table('branches')->where('branch', trim($toImport->branch))->get();

                        if(count($branches) == 0){ 
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Branch is not in the Branches List";
                            $excelMember->save();
                            continue;
                        } elseif (count($branches) > 1){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "It matched with More than 2 Branches on the User List, please be specific";
                            $excelMember->save();
                            continue;
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
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Marketting Agent is not in the Users List";
                            $excelMember->save();
                            continue;
                        } elseif (count($users) > 1){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "It matched with More than 2 Marketting Agent on the User List, please be specific";
                            $excelMember->save();
                            continue;
                        }

                        $user_id = $users[0]->id;

                    //

                    // 4. Validate Status
                        if((strtolower(trim($toImport->status)) != "active") 
                         && strtolower(trim($toImport->status)) != "collector"){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Status should be Active or Collector";
                            $excelMember->save();
                            continue;
                        }
                        $status = trim($toImport->status);
                    //

                    // 5. Validate PH/Member
                    
                        // Get Next Auto Increment
                        $statement = DB::select("SHOW TABLE STATUS LIKE 'members'");
                        $member_id = $statement[0]->Auto_increment;
                        
                        $fullname = $this->parseFullName(trim($toImport->phmember));

                        $members = DB::table('members')
                        ->where('lname', '=', $fullname['lname'])
                        ->where('fname', '=', $fullname['fname'])
                        ->where('mname', '=', $fullname['mname'])
                        ->where('ext', '=', $fullname['ext'])
                        ->get();

                        if(count($members) == 0){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Member is not existing in Member's List";
                            $excelMember->save();
                            continue;
                        } elseif (count($members) > 1){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "It matched with More than 2 Members on the Member List, please be specific";
                            $excelMember->save();
                            continue;
                        }

                        $member = Member::find($members[0]->id);

                    //

                    // 6. Validate Address
                        $member->address = trim($toImport->address);
                    //

                    // 7. Validate Civil Status
                        $member->civil_status = trim($toImport->civil_status);
                    //

                    // 8. Validate Birthdate
                        $birthdate = $this->excelToMySQLDateTime(trim($toImport->birthdate));

                        if($birthdate == null){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Invalid Birthdate Value!";
                            $excelMember->save();
                            continue;
                        }
                    
                        $member->birthdate = $birthdate;
                    //

                    // 9. Validate Claimants Details (Name and Contact)
                        $fullname = $this->parseFullName(trim($toImport->name));

                        $claimants = DB::table('claimants')
                        ->where('lname', $fullname['lname'])
                        ->where('mname', $fullname['mname'])
                        ->where('fname', $fullname['fname'])
                        ->where('ext', $fullname['ext'])->get();

                        // Get Next Auto Increment
                        $statement = DB::select("SHOW TABLE STATUS LIKE 'claimants'");
                        $claimant_id = $statement[0]->Auto_increment;

                        if(count($claimants) == 0){
                            
                            $claimant = new Claimant();

                            $claimant->fname = $fullname['fname'];
                            $claimant->mname = $fullname['mname'];
                            $claimant->lname = $fullname['lname'];
                            $claimant->ext = $fullname['ext'];

                            $claimant->contact_num = $toImport->contact_num;

                        } elseif(count($claimants) > 1) {

                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "More than 1 Claimants, Existing for Member!";
                            $excelMember->save();
                            continue;
                        
                        } else {

                            $claimant = Claimant::find($claimants[0]->id);
                            $claimant->contact_num = $toImport->contact_num;
                            $claimant_id = $claimants[0]->id;

                        }

                    //

                    // 10. Validate Type of Transaction
                        if((strtolower(trim($toImport->type_of_transaction)) != "new sales") 
                         && strtolower(trim($toImport->status)) != "reactivation"){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Transaction should be New Sales or Reactivation";
                            $excelMember->save();
                            continue;
                        }

                        $transaction_type = trim($toImport->type_of_transaction);
                    //

                    // 11. Validate Registration Amount
                        if(is_numeric(trim($toImport->registration_amount))){
                            $registration_fee = (float)trim($toImport->registration_amount);
                        } else {
                            $registration_fee = null;
                        }
                    //

                    // 12. Validate Dayong Program
                        $programs = DB::table('programs')
                        ->where('code', '=', trim($toImport->dayong_program))->get();

                        if(count($programs) == 0){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Program not Existing in Settings!";
                            $excelMember->save();
                            continue;
                        } elseif (count($programs) > 1){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "More than 2 Programs found in Settings!";
                            $excelMember->save();
                            continue;
                        } else {
                            $program_id = $programs[0]->id;
                        }

                    //

                    // 13. Validation Application No.
                        $query = DB::table('members_program')->where('app_no', '=', trim($toImport->application_no))->get();

                        if (trim($toImport->application_no) == ""){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Missing Application No.!";
                            $excelMember->save();
                            continue;
                        } elseif (count($query) > 0){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Application No. is already existing!";
                            $excelMember->save();
                            continue;
                        } elseif (!is_numeric(trim($toImport->application_no))){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Application No. is not a Number!";
                            $excelMember->save();
                            continue;
                        } 
                        $app_no = (int)trim($toImport->application_no);
                    //

                    // 14. Validate OR Number
                        $query = DB::table('members_program')->where('or_number', '=', trim($toImport->or_number))->get();

                        if (trim($toImport->or_number) == ""){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Missing OR Number!";
                            $excelMember->save();
                            continue;
                        } elseif (count($query) > 0){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "OR Number is already existing!";
                            $excelMember->save();
                            continue;
                        } elseif (!is_numeric(trim($toImport->or_number))){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "OR Number is not a Number!";
                            $excelMember->save();
                            continue;
                        } 
                        $or_number = (int)trim($toImport->or_number);
                    //

                    // 15. Validate OR Date
                        $or_date = $this->excelToMySQLDateTime(trim($toImport->or_date));

                        if($or_date == null){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Invalid OR Date Value!";
                            $excelMember->save();
                            continue;
                        }
                    //

                    // 16. Validate Amount Collected
                        if (trim($toImport->amount_collected) == ""){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Missing Amount Collected!";
                            $excelMember->save();
                            continue;
                        } elseif (!is_numeric(trim($toImport->amount_collected))){
                            $excelMember = ExcelMembers::find($toImport->id);
                            $excelMember->remarks = "Amount is not a Number!";
                            $excelMember->save();
                            continue;
                        } 
                        $amount = (int)trim($toImport->amount_collected);
                    //

                    // 17. Validate Beneficiaries
                        $names = array($toImport->name1, $toImport->name2, $toImport->name3, $toImport->name4, $toImport->name5);
                        $relationships = array($toImport->relationship1, $toImport->relationship2, 
                        $toImport->relationship3, $toImport->relationship4, $toImport->relationship5);
                        $beneficiaries_ids = '';

                        for($i = 0; $i <= count($names) - 1; $i++){
                            if(trim($names[$i]) == "") continue;
                            
                            $fullnames = $this->parseFullName($names[$i]);
                            $query = DB::table('beneficiaries')
                            ->where('lname', $fullnames['lname'])
                            ->where('mname', $fullnames['mname'])
                            ->where('fname', $fullnames['fname'])
                            ->where('ext', $fullnames['ext'])
                            ->get();

                            if(count($query) > 1){
                                $excelMember = ExcelMembers::find($toImport->id);
                                $excelMember->remarks = "More than 2 Beneficiaries found with the same record.";
                                $excelMember->save();
                                continue;
                            } elseif(count($query) == 1){
                                
                                $beneficiaries_ids = $beneficiaries_ids . ',' . $query[0]->id;
                                
                            } else {

                                // Get Next Auto Increment
                                $statement = DB::select("SHOW TABLE STATUS LIKE 'beneficiaries'");
                                $beneficiary_id = $statement[0]->Auto_increment;

                                $beneficiary = new Beneficiary();

                                $beneficiary->fname = $fullnames['fname'];
                                $beneficiary->mname = $fullnames['mname'];
                                $beneficiary->lname = $fullnames['lname'];
                                $beneficiary->ext = $fullnames['ext'];
                                $beneficiary->relationship = $relationships[$i];

                                $beneficiary->save();

                                $beneficiaries_ids = $beneficiaries_ids . ',' . $beneficiary_id;

                            }

                        }

                    //

                //
                
                // IMPORT TO NEW SALES TABLE (MEMBER PROGRAM)
                
                    $member_program = new MembersProgram();

                    $member_program->app_no = $app_no;
                    $member_program->user_id = $my_user->id;
                    $member_program->agent_id = $user_id;
                    $member_program->member_id = $member->id;
                    $member_program->program_id = $program_id;
                    $member_program->branch_id = $branch_id;
                    $member_program->claimant_id = $claimant_id;
                    $member_program->beneficiaries_ids = $beneficiaries_ids;
                    $member_program->or_number = $or_number;
                    $member_program->or_date = $or_date;
                    $member_program->registration_fee = $registration_fee;
                    $member_program->amount = $amount;
                    $member_program->transaction_type = $transaction_type;
                    $member_program->status = $status;
                    $member_program->created_at = $timestamp;
                    $member_program->save();

                    $member->save();
                    $claimant->save();

                    $excelMember = ExcelMembers::find($toImport->id);
                    $excelMember->remarks = "";
                    $excelMember->isImported = true;
                    $excelMember->save();

                //
            }
        }

        // Back to View
        return redirect('/new-sales')->with("success_msg","Created Successfully"); 
    }

    public function viewDetails($id)
    {
        $my_user = auth()->user();
        $member_program = DB::table('members_program')->where('id', $id)->get()[0];
        $member = DB::table('members')->where('id', $member_program->member_id)->where('is_deleted', false)->get();
        $members = DB::table('members')->where('is_deleted', false)->get();
        $branches = DB::table('branches')->where('id', $member_program->branch_id)->get()[0];
        $programs = DB::table('programs')->where('id', $member_program->program_id)->get()[0];
        $users = DB::table('users')->where('usertype', 3)->get();

        if( $member[0]->claimants_id != "" &&  $member[0]->claimants_id != null){
            $claimants = DB::table('claimants')->where('id', $member[0]->claimants_id)->get();
            $claimant = $claimants[0];
        } else {
            $claimant = null;
        }

        if($member[0]->beneficiaries_ids != ""){
            $arr = explode(",", $member[0]->beneficiaries_ids); array_pop($arr);
            $beneficiaries = DB::table('beneficiaries')->whereIn('id', $arr)->get();
        } else {
            $beneficiaries = array();
        }

        if(auth()->check()){

            return view('dashboard-contents.modules.new-sales-view', [
                'id' => $id,
                'member' => $member[0],
                'members' => $members,
                'claimant' => $claimant,
                'beneficiaries' => $beneficiaries,
                'member_program' => $member_program,
                'users' => $users,
                'branches' => $branches,
                'programs' => $programs,
                'users' => $users,
            ]);

        } 
    }

    public function editDetails($id)
    {
        $my_user = auth()->user();
        $member_program = DB::table('members_program')->where('id', $id)->get()[0];
        $member = DB::table('members')->where('id', $member_program->member_id)->where('is_deleted', false)->get();
        $members = DB::table('members')->where('is_deleted', false)->get();
        $branches = DB::table('branches')->get();
        $programs = DB::table('programs')->get();
        $users = DB::table('users')->where('usertype', 3)->get();

        if( $member[0]->claimants_id != "" &&  $member[0]->claimants_id != null){
            $claimants = DB::table('claimants')->where('id', $member[0]->claimants_id)->get();
            $claimant = $claimants[0];
        } else {
            $claimant = null;
        }

        if($member[0]->beneficiaries_ids != ""){
            $arr = explode(",", $member[0]->beneficiaries_ids); array_pop($arr);
            $beneficiaries = DB::table('beneficiaries')->whereIn('id', $arr)->get();
        } else {
            $beneficiaries = array();
        }

        if(auth()->check()){

            return view('dashboard-contents.modules.new-sales-edit', [
                'id' => $id,
                'member' => $member[0],
                'members' => $members,
                'claimant' => $claimant,
                'beneficiaries' => $beneficiaries,
                'member_program' => $member_program,
                'users' => $users,
                'branches' => $branches,
                'programs' => $programs,
                'users' => $users,
            ]);

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
            'fname' => ucwords(strtolower($firstName)),
            'mname' => ucwords(strtolower($middleName)),
            'lname' => ucwords(strtolower($lastName)),
            'ext' => ucwords(strtolower($nameExtension))
        ];
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

    public function excelToMySQLDateTime($excelDate) {
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

    /**
     * Validation Functions
     */
    public function checkMemberPrograms(Request $request)
    {
        $request->validate([
            'member_id' => ['required', 'integer'],
        ]);

        // Get program descriptions already registered by this member
        $descriptions = MemberProgram::where('member_id', $request->member_id)
            ->join('programs', 'programs.id', '=', 'member_programs.program_id')
            ->pluck('programs.description')
            ->unique()
            ->values();

        return response()->json([
            'registered_descriptions' => $descriptions,
        ]);
    }

}


