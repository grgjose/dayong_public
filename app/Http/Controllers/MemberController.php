<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Events\ProcessExcelFile;
use DateTime;
use App\Jobs\ImportExcelFile;
use App\Imports\SalesImport;
use App\Imports\ExcelImport;
use App\Imports\ExcelMembersImport;
use App\Models\Entry;
use App\Models\Member;
use App\Models\MembersProgram;
use App\Models\Program;
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
use Carbon\Carbon;
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

class MemberController extends Controller
{
    public function index()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $users = DB::table('users')->orderBy('usertype', 'asc')->get();
            $members = DB::table('members')->where('is_deleted', false)->orderBy('created_at', 'desc')->get();
            $programs = DB::table('programs')->orderBy('code')->get();
            $branches = DB::table('branches')->orderBy('branch')->get();

            return view('main', [
                'my_user' => $my_user,
                'members' => $members,
                'programs' => $programs,
                'branches' => $branches,
                'users' => $users,
            ])
            ->with('header_title', 'Membership Registration')
            ->with('subview', 'dashboard-contents.modules.members');

        } else {
            return redirect('/');
        }
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/');
        }

        $my_user = auth()->user();

        // ✅ VALIDATION
        $validated = $request->validate([

            // Personal Information
            "fname" => ['required','string','max:255'],
            "mname" => ['nullable','string','max:255'],
            "lname" => ['required','string','max:255'],
            "ext" => ['nullable','string','max:50'],
            "birthdate" => ['required','date'],
            "sex" => ['required','in:MALE,FEMALE'],
            "birthplace" => ['nullable','string','max:255'],
            "citizenship" => ['required','string','max:255'],
            "civil_status" => ['required','string','max:50'],
            "contact_num" => ['required','string','max:20'],
            "email" => ['nullable','email','max:255'],
            "address" => ['required','string','max:500'],

            // Claimant's Personal Information
            "fname_c" => ['required','string','max:255'],
            "mname_c" => ['required','string','max:255'],
            "lname_c" => ['required','string','max:255'],
            "ext_c" => ['nullable','string','max:50'],
            "birthdate_c" => ['required','date'],
            "sex_c" => ['required','in:MALE,FEMALE'],
            "contact_num_c" => ['required','string','max:20'],

            // Location and Program
            "program_id" => ['required','integer', 'exists:programs,id'],
            "branch_id" => ['required','integer'],
            "or_number" => ['required','string','max:100'],
            "or_date" => ['required','date'],
            "app_no" => ['required','string','max:100'],
            "member_id" => ['nullable'],

            // Others
            "registration_fee" => ['nullable','numeric'],
            "agent_id" => ['nullable','integer'],
            "amount" => ['nullable','numeric'],
            "incentives" => ['nullable','numeric'],

            // Beneficiaries
            'beneficiaries' => ['sometimes','array','max:10'],

            'beneficiaries.*.fname' => ['required','string','max:255'],
            'beneficiaries.*.mname' => ['nullable','string','max:255'],
            'beneficiaries.*.lname' => ['required','string','max:255'],
            'beneficiaries.*.ext' => ['nullable','string','max:50'],

            'beneficiaries.*.birthdate' => ['required','date'],
            'beneficiaries.*.sex' => ['required','in:MALE,FEMALE'],
            'beneficiaries.*.relationship' => ['required','string','max:255'],
            'beneficiaries.*.contact_num' => ['required','string','max:20'],
        ]);

        $this->validateProgramAge($request->birthdate, $request->program_id);

        // ✅ Convert ALL STRING values to ALL CAPS (including nested beneficiaries)
        array_walk_recursive($validated, function (&$value) {
            if (is_string($value)) {
                $value = mb_strtoupper(trim($value));
            }
        });

        // ✅ Normalize contact numbers (digits only)
        $validated['contact_num']   = preg_replace('/\D+/', '', $validated['contact_num']);
        $validated['contact_num_c'] = preg_replace('/\D+/', '', $validated['contact_num_c']);

        foreach ($validated['beneficiaries'] ?? [] as $k => $item) {
            $validated['beneficiaries'][$k]['contact_num'] = preg_replace('/\D+/', '', $item['contact_num']);
        }

        try {
            DB::transaction(function () use ($validated, $my_user, &$member) {

                // ✅ MEMBER
                    $member = new Member();
                    $member->fname = $validated['fname'];
                    $member->mname = $validated['mname'] ?? null;
                    $member->lname = $validated['lname'];
                    $member->ext = $validated['ext'] ?? null;
                    $member->contact_num = $validated['contact_num'];
                    $member->email = $validated['email'] ?? null;
                    $member->birthdate = $validated['birthdate'];
                    $member->sex = $validated['sex'];
                    $member->birthplace = $validated['birthplace'];
                    $member->citizenship = $validated['citizenship'];
                    $member->civil_status = $validated['civil_status'];
                    $member->address = $validated['address'];
                    $member->agent_id = $validated['agent_id'] ?? null;
                    $member->encoder_id = $my_user->id;
                    $member->branch_id = $validated['branch_id'];
                //

                // ✅ CLAIMANT
                    $claimant = new Claimant();
                    $claimant->fname = $validated['fname_c'];
                    $claimant->mname = $validated['mname_c'];
                    $claimant->lname = $validated['lname_c'];
                    $claimant->ext = $validated['ext_c'] ?? null;
                    $claimant->birthdate = $validated['birthdate_c'];
                    $claimant->sex = $validated['sex_c'];
                    $claimant->contact_num = $validated['contact_num_c'];
                    $claimant->save();

                    $member->claimant_id = $claimant->id;
                    $member->save();
                //

                // ✅ BENEFICIARIES (Many-to-Many attach)
                foreach ($validated['beneficiaries'] ?? [] as $item) {

                    /**
                     * ✅ Find or Create Beneficiary
                     * Adjust "matching rules" depending on your real uniqueness.
                     * Here: same fname + lname + birthdate = same person
                     */
                    $beneficiary = Beneficiary::firstOrCreate(
                        [
                            'fname' => $item['fname'],
                            'lname' => $item['lname'],
                            'birthdate' => $item['birthdate'],
                        ],
                        [
                            'mname' => $item['mname'] ?? null,
                            'ext' => $item['ext'] ?? null,
                            'sex' => $item['sex'],
                            'contact_num' => $item['contact_num'],
                        ]
                    );

                    // ✅ Attach without duplicating pivot row
                    $member->beneficiaries()->syncWithoutDetaching([
                        $beneficiary->id => [
                            'relationship' => $item['relationship'],
                        ]
                    ]);
                }

                // ✅ NEW SALES
                $newsales = new MembersProgram();
                $newsales->member_id = $member->id;
                $newsales->program_id = $validated['program_id'];
                $newsales->branch_id = $validated['branch_id'];
                $newsales->or_number = $validated['or_number'];
                $newsales->or_date = $validated['or_date'];
                $newsales->app_no = $validated['app_no'];
                $newsales->registration_fee = $validated['registration_fee'] ?? 0;
                $newsales->amount = $validated['amount'] ?? 0;
                $newsales->incentives = $validated['incentives'] ?? 0;
                $newsales->encoder_id = $my_user->id;
                $newsales->agent_id = $validated['agent_id'] ?? null;
                $newsales->save();
            });

            return redirect('/members')
                ->with("success_msg", ($member->lname ?? 'MEMBER') . " Member Created Successfully");

        } catch (\Throwable $e) {
            return back()
                ->with("error_msg", "Failed to create member. Error: " . $e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect('/');
        }

        $my_user = auth()->user();

        // ✅ VALIDATION (matches your new edit form structure)
        $validated = $request->validate([

            // Member
            "fname" => ['required','string','max:255'],
            "mname" => ['nullable','string','max:255'],
            "lname" => ['required','string','max:255'],
            "ext" => ['nullable','string','max:50'],
            "birthdate" => ['required','date'],
            "sex" => ['required','in:MALE,FEMALE'],
            "birthplace" => ['required','string','max:255'],
            "citizenship" => ['required','string','max:255'],
            "civil_status" => ['required','string','max:50'],
            "contact_num" => ['required','string','max:20'],
            "email" => ['nullable','email','max:255'],
            "address" => ['required','string','max:500'],

            // Claimant
            "fname_c" => ['required','string','max:255'],
            "mname_c" => ['required','string','max:255'],
            "lname_c" => ['required','string','max:255'],
            "ext_c" => ['nullable','string','max:50'],
            "birthdate_c" => ['required','date'],
            "sex_c" => ['required','in:MALE,FEMALE'],
            "contact_num_c" => ['required','string','max:20'],

            // Beneficiaries (array)
            'beneficiaries' => ['sometimes','array','max:10'],

            // beneficiary id is optional (if you later allow adding new)
            'beneficiaries.*.id' => ['nullable','integer'],

            'beneficiaries.*.fname' => ['required','string','max:255'],
            'beneficiaries.*.mname' => ['nullable','string','max:255'],
            'beneficiaries.*.lname' => ['required','string','max:255'],
            'beneficiaries.*.ext' => ['nullable','string','max:50'],
            'beneficiaries.*.birthdate' => ['required','date'],
            'beneficiaries.*.sex' => ['required','in:MALE,FEMALE'],
            'beneficiaries.*.relationship' => ['required','string','max:255'],
            'beneficiaries.*.contact_num' => ['required','string','max:20'],
        ]);

        //$this->validateProgramAge($request->birthdate, $request->program_id);

        // ✅ Convert ALL strings to ALL CAPS
        array_walk_recursive($validated, function (&$value) {
            if (is_string($value)) {
                $value = mb_strtoupper(trim($value));
            }
        });

        // ✅ Normalize contact numbers (digits only)
        $validated['contact_num']   = preg_replace('/\D+/', '', $validated['contact_num']);
        $validated['contact_num_c'] = preg_replace('/\D+/', '', $validated['contact_num_c']);

        foreach ($validated['beneficiaries'] ?? [] as $k => $item) {
            $validated['beneficiaries'][$k]['contact_num'] = preg_replace('/\D+/', '', $item['contact_num']);
        }

        try {
            DB::transaction(function () use ($validated, $id, $my_user) {

                // ✅ Load Member with claimant + beneficiaries
                $member = Member::with(['claimant', 'beneficiaries'])->findOrFail($id);

                // ✅ Update Member
                $member->fname = $validated['fname'];
                $member->mname = $validated['mname'] ?? null;
                $member->lname = $validated['lname'];
                $member->ext = $validated['ext'] ?? null;
                $member->birthdate = $validated['birthdate'];
                $member->sex = $validated['sex'];
                $member->birthplace = $validated['birthplace'];
                $member->citizenship = $validated['citizenship'];
                $member->civil_status = $validated['civil_status'];
                $member->contact_num = $validated['contact_num'];
                $member->email = $validated['email'] ?? null;
                $member->address = $validated['address'];
                $member->agent_id = $member->agent_id; // keep existing unless you allow editing
                $member->encoder_id = $my_user->id;     // optional: update who edited
                $member->save();

                // ✅ Update Claimant
                // if claimant doesn't exist, create one
                if ($member->claimant) {
                    $claimant = $member->claimant;
                } else {
                    $claimant = new Claimant();
                }

                $claimant->fname = $validated['fname_c'];
                $claimant->mname = $validated['mname_c'];
                $claimant->lname = $validated['lname_c'];
                $claimant->ext = $validated['ext_c'] ?? null;
                $claimant->birthdate = $validated['birthdate_c'];
                $claimant->sex = $validated['sex_c'];
                $claimant->contact_num = $validated['contact_num_c'];
                $claimant->save();

                // Ensure member has claimant_id linked
                if (!$member->claimant_id) {
                    $member->claimant_id = $claimant->id;
                    $member->save();
                }

                // ✅ Update Beneficiaries + Pivot relationship
                // Build list of beneficiary ids that should remain attached
                $keepBeneficiaryIds = [];

                foreach ($validated['beneficiaries'] ?? [] as $item) {

                    // If existing beneficiary ID is provided, update it
                    if (!empty($item['id'])) {

                        $beneficiary = Beneficiary::find($item['id']);

                        // If beneficiary id not found, fallback to create new
                        if (!$beneficiary) {
                            $beneficiary = new Beneficiary();
                        }

                    } else {
                        // If no id, create a new beneficiary record
                        $beneficiary = new Beneficiary();
                    }

                    $beneficiary->fname = $item['fname'];
                    $beneficiary->mname = $item['mname'] ?? null;
                    $beneficiary->lname = $item['lname'];
                    $beneficiary->ext = $item['ext'] ?? null;
                    $beneficiary->birthdate = $item['birthdate'];
                    $beneficiary->sex = $item['sex'];
                    $beneficiary->contact_num = $item['contact_num'];
                    $beneficiary->save();

                    // Attach / Update pivot relationship
                    $member->beneficiaries()->syncWithoutDetaching([
                        $beneficiary->id => [
                            'relationship' => $item['relationship'],
                        ]
                    ]);

                    $keepBeneficiaryIds[] = $beneficiary->id;
                }

                // ✅ Optional: remove beneficiaries that were removed from the form
                // This will only detach the relation, it will NOT delete beneficiary record.
                $member->beneficiaries()->sync($keepBeneficiaryIds);

            });

            return redirect('/members')->with("success_msg", "Member Updated Successfully");

        } catch (\Throwable $e) {
            return back()
                ->with("error_msg", "Failed to update member. Error: " . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Request $request)
    {
        if(auth()->check()){

            $memberProgramId = DB::table('members_program')
            ->where('member_id', $request->input("id"))
            ->where('is_deleted', false)->get();

            // Destroy Request Data (Soft Delete)
            $member = Member::find($request->input("id"));
            $member->is_deleted = true;
            $member->save();

            if(count($memberProgramId) > 0){
                $memberProgram = MembersProgram::find($memberProgramId[0]->id);
                $memberProgram->is_deleted = true;
                $memberProgram->save();
            }

            /*
            foreach($memberProgram as $mp){
                Claimant::where("id", $mp->claimants_id)->delete();
                if($mp->beneficiaries_ids != ""){
                    $temp = explode(",", $mp->beneficiaries_ids);
                    foreach($temp as $t){
                        Beneficiary::where("id", (int)$t)->delete();
                    }
                }
            }
            */

            return redirect('/members')->with("success_msg", "Deleted Successfully");

        } else {
            return redirect('/');
        }
    }

    public function upload(Request $request)
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $validated = $request->validate([
                'upload_file' => ['required'],
                'sheetName' => ['required'],
            ]);

            $users = DB::table('users')->where('lname', strtolower($validated['sheetName']))->get();

            if(count($users) == 0){
                return redirect('/members')->with("error_msg", "No User with Last Name ".$validated['sheetName']);
            }

            $user = $users[0];
            $col['phmember'] = 3;
            $col['address'] = 4;

            $import = new ExcelMembersImport($validated['sheetName']);
            $array = Excel::toCollection($import, $validated['upload_file']);
            $spreadsheet = $array[$validated['sheetName']];
            $enabledReading = false;  
            $names = array();
            $addresses = array();

            for($cnt = 0; $cnt <= count($spreadsheet) - 1; $cnt++){

                $phmember = trim($spreadsheet[$cnt][$col['phmember']]);
                $address = trim($spreadsheet[$cnt][$col['address']]);

                if($enabledReading == true){#ProjectKaizen

                    if( $phmember != "" && 
                        $phmember != null && 
                        $phmember != "SUSPENDED ACCOUNTS" && 
                        $phmember != "FORFEITED / DROPPED ACCOUNTS"){

                        $fullname = $this->parseFullName($phmember);
                        array_push($names, $fullname);
                        array_push($addresses, $address);
                    }
                }
                if($phmember == 'PH/MEMBER'){ $enabledReading = true; }
            }

            for($cnt = 0; $cnt <= count($names) - 1; $cnt++){

                $fullname = $names[$cnt];
                $address = trim($addresses[$cnt]);

                $member = new Member();
                $member->fname = trim($fullname['fname']);
                $member->mname = trim($fullname['mname']);
                $member->lname = trim($fullname['lname']);
                $member->ext = trim($fullname['ext']);
                $member->address = $address;
                $member->agent_id = $user->id;
                $member->lastUpdatedBy = $my_user->id;
                $member->save();

            }

            return redirect('/members')->with("success_msg", "Uploaded Successfully");

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
            'fname' => ucwords(strtolower($firstName)),
            'mname' => ucwords(strtolower($middleName)),
            'lname' => ucwords(strtolower($lastName)),
            'ext' => ucwords(strtolower($nameExtension))
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

    public function print($id)
    {

        
        $member = DB::table('members')->where('id', $id)->get()[0];
        $members_program = DB::table('members_program')->where('member_id', $id)->get();
        $entries = DB::table('entries')->where('member_id', $id)->get();

        if(count($members_program) == 0 && count($entries) == 0){ 
            return redirect('/members')->with("error_msg", "No New Sales or Collection Found");
        }

        if(count($members_program) > 0){
            $my_user = DB::table('users')->where('id', $members_program[0]->user_id)->get()[0];
        } else {
            $my_user = null;
        }
        
        $branches = DB::table('branches')->orderBy('id')->get();
        $users = DB::table('users')->orderBy('id')->get();
        
        $pdf = Pdf::loadView('forms.statement_of_account', [
            'members_program' => $members_program,
            'member' => $member,
            'branches' => $branches,
            'users' => $users,
            'monthAndYear' => date('F Y'),
            'date' => date('m/d/Y'),
            'cashier' => $my_user->fname.' '.$my_user->lname,
            'entries' => $entries,
        ]);

        return $pdf->stream('statementOfAccount'. date('m/d/Y') .'.pdf');
    }

    public function viewDetails($id)
    {
        if (!auth()->check()) {
            return redirect('/');
        }

        // Member + Claimant + Beneficiaries (from pivot)
        $member = Member::with([
            'claimant',
            'beneficiaries' => function ($q) {
                $q->withPivot('relationship');
            }
        ])->findOrFail($id);

        $users = DB::table('users')->get();

        return view('dashboard-contents.modules.members-view', [
            'id' => $id,
            'member' => $member,
            'claimant' => $member->claimant,            // ✅ from relationship
            'beneficiaries' => $member->beneficiaries,  // ✅ many-to-many
            'users' => $users,
        ]);
    }

    public function editDetails($id)
    {
        if (!auth()->check()) {
            return redirect('/');
        }

        // ✅ Load member + claimant + beneficiaries with pivot relationship
        $member = Member::with([
            'claimant',
            'beneficiaries' => function ($q) {
                $q->withPivot('relationship');
            }
        ])->findOrFail($id);

        // ✅ Get ALL users (or keep your filter if needed)
        $users = User::orderBy('usertype', 'asc')->get();

        return view('dashboard-contents.modules.members-edit', [
            'id' => $id,
            'member' => $member,
            'claimant' => $member->claimant,
            'beneficiaries' => $member->beneficiaries,
            'users' => $users,
        ]);
    }

    public function import(Request $request)
    {
        set_time_limit(240);
        $programs = DB::table('programs')->orderBy('code')->get();
        $branches = DB::table('branches')->orderBy('branch')->get();
        $toImportMembers = DB::table('excel_members')->orderBy('id')->skip(0)->take((int)$request->input('data_count'))->get();

        foreach($toImportMembers as $toImport) {
            if($toImport->timestamp != "" && trim(strtolower($toImport->type_of_transaction)) == "new sales"){

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

                // Get Next Auto Increment
                $statement = DB::select("SHOW TABLE STATUS LIKE 'members'");
                $member_id = $statement[0]->Auto_increment;
                
                // Save Request Data (Member's Personal Information)
                $member = new Member();

                $name = ucwords(strtolower(trim($toImport->phmember)), " .");
                if(strpos($name, ",") > 0){
                    $tmp = explode(",", $name);
                    $member->fname = ucwords($tmp[1]);
                    $member->lname = ucwords($tmp[0]);
                } else {
                    $member->fname = substr($name, strpos($name, " ") + 1);
                    $member->lname = substr($name, 0, strpos($name, " "));
                }

                $members = DB::table('members')
                ->where('lname', $member->lname)
                ->where('fname', 'LIKE', $member->fname)
                ->get();

                //$anotherMembers = DB::table('members')->where('or_number', $toImport->or_number)->get();
                //$excel_members = DB::table('excel_members')->where('or_number', $toImport->or_number)->get();

                if(count($members) == 0){

                    if(trim($toImport->birthdate) != ""){
                        $birthdate = $this->excelTimestampToString((float)trim($toImport->birthdate));
                        //$member->birthdate = new DateTime($birthdate);
                        //$member->birthdate->format('Y-m-d H:i:s');
                        $member->birthdate = $birthdate;
                    }

                    $member->civil_status = ucwords(strtolower(trim($toImport->civil_status)), " .");
                    $member->contact_num = ucwords(strtolower(trim($toImport->contact_num)), " .");
                    $member->address = strval(ucwords(strtolower(trim($toImport->address)), " ."));

                    if(is_numeric($toImport->timestamp)){
                        $timestamp = $this->excelTimestampToString((float)trim($toImport->timestamp));
                    } else {
                        break;
                    }
                    
                    $member->created_at = $timestamp;

                    try{
                        $member->save();
                    }
                    catch(Exception $e){
                        dd($e->getMessage());   // insert query
                    }

                    // Get Next Auto Increment
                    $statement = DB::select("SHOW TABLE STATUS LIKE 'claimants'");
                    $claimants_id = $statement[0]->Auto_increment;

                    // Save Request Data (Member's Claimants Information)
                    $claimant = new Claimant();

                    $name = ucwords(strtolower(trim($toImport->name)), " .");
                    if(strpos($name, ",") > 0){
                        $tmp = explode(",", $name);
                        $claimant->fname = ucwords($tmp[1]);
                        $claimant->lname = ucwords($tmp[0]);
                    } else {
                        $claimant->fname = substr($name, strpos($name, " ") + 1);
                        $claimant->lname = substr($name, 0, strpos($name, " "));
                    }

                    $claimant->contact_num = $toImport->contact_num;

                    $claimant->save();

                    // Get Next Auto Increment
                    $statement = DB::select("SHOW TABLE STATUS LIKE 'beneficiaries'");
                    $beneficiary_id = $statement[0]->Auto_increment;
                    $beneficiaries_ids = "";

                    // Save Request Data (Member's Beneficiaries Information)

                    if(strtolower($toImport->name1) != ""
                    && strtolower($toImport->name1) != "n/a"
                    && strtolower($toImport->name1) != "na"
                    && strtolower($toImport->name1) != "none"){
                        $beneficiary_1 = new Beneficiary();

                        $name = ucwords(strtolower(trim($toImport->name1)), " .");
                        if(strpos($name, ",") > 0){
                            $tmp = explode(",", $name);
                            $beneficiary_1->fname = ucwords($tmp[1]);
                            $beneficiary_1->lname = ucwords($tmp[0]);
                        } else {
                            $beneficiary_1->fname = substr($name, strpos($name, " ") + 1);
                            $beneficiary_1->lname = substr($name, 0, strpos($name, " "));
                        }

                        $beneficiary_1->relationship = ucwords(strtolower(trim($toImport->relationship1)));

                        $beneficiary_1->save();
                        $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id . ",";
                        $beneficiary_id = $beneficiary_id + 1;
                    }

                    if(strtolower($toImport->name2) != ""
                    && strtolower($toImport->name2) != "n/a"
                    && strtolower($toImport->name2) != "na"
                    && strtolower($toImport->name2) != "none"){
                        $beneficiary_2 = new Beneficiary();

                        $name = ucwords(strtolower(trim($toImport->name2)), " .");
                        if(strpos($name, ",") > 0){
                            $tmp = explode(",", $name);
                            $beneficiary_2->fname = ucwords($tmp[1]);
                            $beneficiary_2->lname = ucwords($tmp[0]);
                        } else {
                            $beneficiary_2->fname = substr($name, strpos($name, " ") + 1);
                            $beneficiary_2->lname = substr($name, 0, strpos($name, " "));
                        }

                        $beneficiary_2->relationship = ucwords(strtolower(trim($toImport->relationship2)));

                        $beneficiary_2->save();
                        $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id . ",";
                        $beneficiary_id = $beneficiary_id + 1;
                    }

                    if(strtolower($toImport->name3) != ""
                    && strtolower($toImport->name3) != "n/a"
                    && strtolower($toImport->name3) != "na"
                    && strtolower($toImport->name3) != "none"){
                        $beneficiary_3 = new Beneficiary();

                        $name = ucwords(strtolower(trim($toImport->name3)), " .");
                        if(strpos($name, ",") > 0){
                            $tmp = explode(",", $name);
                            $beneficiary_3->fname = ucwords($tmp[1]);
                            $beneficiary_3->lname = ucwords($tmp[0]);
                        } else {
                            $beneficiary_3->fname = substr($name, strpos($name, " ") + 1);
                            $beneficiary_3->lname = substr($name, 0, strpos($name, " "));
                        }

                        $beneficiary_3->relationship = ucwords(strtolower(trim($toImport->relationship3)));

                        $beneficiary_3->save();
                        $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id . ",";
                        $beneficiary_id = $beneficiary_id + 1;
                    }

                    if(strtolower($toImport->name4) != ""
                    && strtolower($toImport->name4) != "n/a"
                    && strtolower($toImport->name4) != "na"
                    && strtolower($toImport->name4) != "none"){
                        $beneficiary_4 = new Beneficiary();

                        $name = ucwords(strtolower(trim($toImport->name4)), " .");
                        if(strpos($name, ",") > 0){
                            $tmp = explode(",", $name);
                            $beneficiary_4->fname = ucwords($tmp[1]);
                            $beneficiary_4->lname = ucwords($tmp[0]);
                        } else {
                            $beneficiary_4->fname = substr($name, strpos($name, " ") + 1);
                            $beneficiary_4->lname = substr($name, 0, strpos($name, " "));
                        }

                        $beneficiary_4->relationship = ucwords(strtolower(trim($toImport->relationship4)));

                        $beneficiary_4->save();
                        $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id . ",";
                        $beneficiary_id = $beneficiary_id + 1;
                    }

                    // Save Request Data (Members_Program)
                    $memberProgram = new MembersProgram();
                    $memberProgram->app_no = $toImport->application_no;
                    $memberProgram->user_id = $user_id;
                    $memberProgram->member_id = $member_id;

                    $program_id = 0;
                    foreach($programs as $program){
                        if(strtolower(trim($program->code)) == strtolower(trim($toImport->dayong_program))){
                            $program_id = $program->id; 
                            break; break;
                        }
                    }

                    $branch_id = 0;
                    foreach($branches as $branch){
                        if(strtolower(trim($branch->branch)) == strtolower(trim($toImport->branch))){
                            $branch_id = $branch->id;
                            break; break;
                        }
                    }

                    $memberProgram->program_id = $program_id; 
                    $memberProgram->branch_id = $branch_id;
                    $memberProgram->claimants_id = $claimants_id;
                    $memberProgram->beneficiaries_ids = $beneficiaries_ids;

                    if(is_numeric($toImport->registration_amount)){
                        $memberProgram->registration_fee = $toImport->registration_amount;
                    }

                    $memberProgram->contact_person = ucwords(trim($toImport->name), " .");
                    $memberProgram->contact_person_num = $toImport->contact_num;
                    $memberProgram->status = "active";

                    $memberProgram->save();

                    // Save Request Data (Entry)

                    if((trim($toImport->registration_amount) != "")&&(is_numeric($toImport->registration_amount))){
                        $entry = new Entry();

                        $entry->branch_id = $branch_id;
                        $entry->marketting_agent = $user_id;
                        $entry->member_id = $member_id;
                        $entry->or_number = $toImport->or_number;
                        $entry->amount = $toImport->registration_amount;
                        $entry->number_of_payment = 1;
                        $entry->program_id = $program_id; 
                        $entry->is_reactivated = 0;
                        $entry->is_transferred = 0;
                        $entry->remarks = "REGISTRATION";

                        $entry->save();
                    }

                    $toDelete = ExcelMembers::find($toImport->id);
                    $toDelete->delete();

                }                        
            }
        }

        // Back to View
        return redirect('/members')->with("success_msg","Created Successfully"); 
    }

    public function excelTimestampToString($excelTimestamp) 
    {
        try
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
        catch(TypeError $e){
            dd($excelTimestamp);
        }

    }

    // Validations
    public function checkOrNumber(Request $request)
    {
        $orNumber = $request->query('or_number');

        if (!$orNumber) {
            return response()->json([
                'exists' => false,
                'message' => ''
            ]);
        }

        // Check in MembersProgram table
        $existsInMembersProgram = MembersProgram::where('or_number', $orNumber)->exists();

        // Check in Entries table
        $existsInEntries = Entry::where('or_number', $orNumber)->exists();

        $exists = $existsInMembersProgram || $existsInEntries;

        return response()->json([
            'exists' => $exists,
            'message' => $exists 
                ? "WARNING: OR NUMBER {$orNumber} ALREADY EXISTS!" 
                : ""
        ]);
    }

    public function validateProgram(Request $request)
    {
        $request->validate([
            'birthdate'  => ['required', 'date'],
            'program_id' => ['required'],
            'beneficiaries' => ['nullable', 'array'],
            'beneficiaries.*.id'        => ['required_with:beneficiaries', 'string'],
            'beneficiaries.*.birthdate' => ['required_with:beneficiaries', 'date'],
        ]);

        $program = Program::find($request->program_id);
        $age = Carbon::parse($request->birthdate)->age;

        $validBeneficiaries = [];
        $invalidBeneficiaries = [];

        $messages = [];
        $memberValid = true;
        $valid = true;

        if ($program != null) {
            if(($program->age_min != null && $age < $program->age_min) || ($program->age_max != null && $age > $program->age_max)) {
                $valid = false;
                $memberValid = false;
                $messages[] = "The selected program is not valid for the member's age. Minimum age is {$program->age_min} and maximum age is {$program->age_max}.";
            }

            // Process Only if there are beneficiaries provided in the request
            // ❌ Any beneficiary age invalid
            foreach ($request->beneficiaries ?? [] as $index => $beneficiary) {
                $beneficiaryAge = Carbon::parse($beneficiary['birthdate'])->age;
                if ($beneficiaryAge < $program->ben_age_min || $beneficiaryAge > $program->ben_age_max) {
                    $valid = false;

                    $messages[] = "One or more beneficiaries have invalid ages for the selected program. Beneficiary age must be between {$program->ben_age_min} and {$program->ben_age_max}.";
                    $invalidBeneficiaries[] = [
                        'id' => $beneficiary['id'] ?? "beneficiary_{$index}",
                        'age' => $beneficiaryAge,
                        'message' => "Beneficiary age must be between {$program->ben_age_min} and {$program->ben_age_max}."
                    ];
                } else {
                    $validBeneficiaries[] = [
                        'id' => $beneficiary['id'] ?? "beneficiary_{$index}",
                        'age' => $beneficiaryAge,
                        'message' => "Beneficiary age is valid for the selected program."
                    ];
                }
            }

            if($valid == false){
                return response()->json([
                    'valid' => false,
                    'messages' => $messages,
                    'member_valid' => $memberValid,
                    'validBeneficiaries' => $validBeneficiaries,
                    'invalidBeneficiaries' => $invalidBeneficiaries,
                ]);
            }
        }

        return response()->json([
            'valid' => true,
            'age' => $age,
            'messages' => ['Program is valid for the selected age.'],
        ]);
    }

    private function validateProgramAge($birthdate, $programId)
    {
        $program = Program::findOrFail($programId);

        $age = Carbon::parse($birthdate)->age;

        if (
            (!is_null($program->min_age) && $age < $program->min_age) ||
            (!is_null($program->max_age) && $age > $program->max_age)
        ) {
            abort(422, 'The selected program is not valid for the member’s age.');
        }
    }

    public function checkName(Request $request)
    {
        $request->validate([
            'first_name'  => ['required'],
            'middle_name' => ['nullable'],
            'last_name'   => ['required'],
        ]);

        $firstName = trim($request->first_name);
        $middleName = trim($request->middle_name);
        $lastName = trim($request->last_name);

        $exists = Member::where('fname', $firstName)
            ->where('lname', $lastName)
            ->when($middleName, function ($q) use ($middleName) {
                $q->where('mname', $middleName);
            })
            ->exists();

        // $exists = Member::whereRaw('LOWER(fname) = ?', [strtolower($request->first_name)])
        //     ->whereRaw('LOWER(lname) = ?', [strtolower($request->last_name)])
        //     ->when($request->middle_name, function ($q) use ($request) {
        //         $q->whereRaw('LOWER(mname) = ?', [strtolower($request->middle_name)]);
        //     })
        //     ->exists();

        return response()->json([
            'exists'  => $exists,
            'message' => $exists 
            ? 'A member with the same first name, middle name, and last name already exists.' 
            : 'Cannot find any member with the same first name, middle name, and last name.',
        ]);
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required'],
        ]);

        $exists = Member::where('email', strtolower($request->email))->exists();

        // $exists = Member::whereRaw('LOWER(fname) = ?', [strtolower($request->first_name)])
        //     ->whereRaw('LOWER(lname) = ?', [strtolower($request->last_name)])
        //     ->when($request->middle_name, function ($q) use ($request) {
        //         $q->whereRaw('LOWER(mname) = ?', [strtolower($request->middle_name)]);
        //     })
        //     ->exists();

        return response()->json([
            'exists'  => $exists,
            'message' => $exists 
            ? 'A member with the same email already exists.' 
            : 'Cannot find any member with the same email.',
        ]);
    }

    public function checkAppNo(Request $request)
    {
        $request->validate([
            'app_no' => ['required'],
        ]);

        $exists = MembersProgram::where('app_no', $request->app_no)->exists();

        return response()->json([
            'exists'  => $exists,
            'message' => $exists 
            ? 'An application with the same application number already exists.' 
            : 'Cannot find any application with the same application number.',
        ]);
    }

}
