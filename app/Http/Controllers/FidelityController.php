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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class FidelityController extends Controller
{

    public function index(){
        if(auth()->check()){

            $my_user = auth()->user();
            $users = DB::table('users')->orderBy('id')->get();
            $members = DB::table('members')->orderBy('id')->get();
            $programs = DB::table('programs')->orderBy('code')->get();
            $branches = DB::table('branches')->orderBy('branch')->get();
            $fidelity = DB::table('fidelity')->orderBy('created_at', 'desc')->get();

            return view('main', [
                'my_user' => $my_user,
                'members' => $members,
                'programs' => $programs,
                'branches' => $branches,
                'users' => $users,
                'fidelity' => $fidelity,
            ])
            ->with('header_title', 'Fidelity Monitoring')
            ->with('subview', 'dashboard-contents.modules.fidelity');

        } else {
            return redirect('/');
        }
    }

    public function register(Request $request){

        if(auth()->check()){

            // Get Request Data
            $my_user = auth()->user();
            $validated = $request->validate([
                "user_id" => ['required'],
            ]);

            $user = User::find($validated['user_id']);
            $user->with_fidelity = true;
            $user->save();

            // Back to View
            return redirect('/fidelity')->with("success_msg", "Member Registered Successfully");

        } else {
            return redirect('/');
        }
    }

    public function store(Request $request){

        if(auth()->check()){

            // Get Request Data
            $my_user = auth()->user();
            $validated = $request->validate([

                // Location and Program
                "program_id" => ['required'],
                "branch_id" => ['required'],
                "or_num" => ['required'],

                // Personal Information
                "fname" => ['required'],
                "mname" => ['required'],
                "lname" => ['required'],
                "ext" => ['nullable'],
                "birthdate" => ['required'],
                "sex" => ['required'],
                "birthplace" => ['required'],
                "citizenship" => ['required'],
                "civil_status" => ['required'],
                "contact_num" => ['required'],
                "email" => ['nullable'],
                "address" => ['required'],
                
                // Claimant's Personal Information
                "fname_c" => ['required'],
                "mname_c" => ['required'],
                "lname_c" => ['required'],
                "ext_c" => ['nullable'],
                "birthdate_c" => ['required'],
                "sex_c" => ['required'],
                "contact_num_c" => ['required'],

                // Beneficiaries's #1 Personal Information
                "fname_b1" => ['nullable'],
                "mname_b1" => ['nullable'],
                "lname_b1" => ['nullable'],
                "ext_b1" => ['nullable'],
                "birthdate_b1" => ['nullable'],
                "sex_b1" => ['nullable'],
                "relationship_b1" => ['nullable'],
                "contact_num_b1" => ['nullable'],

                // Beneficiaries's #2 Personal Information
                "fname_b2" => ['nullable'],
                "mname_b2" => ['nullable'],
                "lname_b2" => ['nullable'],
                "ext_b2" => ['nullable'],
                "birthdate_b2" => ['nullable'],
                "sex_b2" => ['nullable'],
                "relationship_b2" => ['nullable'],
                "contact_num_b2" => ['nullable'],
                
                // Others
                "contact_person" => ['required'],
                "contact_person_num" => ['required'],
                "registration_fee" => ['nullable'],

            ]);

            // Get Next Auto Increment
            $statement = DB::select("SHOW TABLE STATUS LIKE 'members'");
            $member_id = $statement[0]->Auto_increment;
            
            // Save Request Data (Member's Personal Information)
            $member = new Member();

            $member->fname = $validated['fname'];
            $member->mname = $validated['mname'];
            $member->lname = $validated['lname'];
            $member->ext = $validated['ext'];
            $member->birthdate = $validated['birthdate'];
            $member->sex = $validated['sex'];
            $member->birthplace = $validated['birthplace'];
            $member->citizenship = $validated['citizenship'];
            $member->civil_status = $validated['civil_status'];
            $member->contact_num = $validated['contact_num'];
            $member->email = $validated['email'];
            $member->address = $validated['address'];

            $member->save();

            // Get Next Auto Increment
            $statement = DB::select("SHOW TABLE STATUS LIKE 'claimants'");
            $claimants_id = $statement[0]->Auto_increment;

            // Save Request Data (Member's Claimants Information)
            $claimant = new Claimant();

            $claimant->fname = $validated['fname_c'];
            $claimant->mname = $validated['mname_c'];
            $claimant->lname = $validated['lname_c'];
            $claimant->ext = $validated['ext_c'];
            $claimant->birthdate = $validated['birthdate_c'];
            $claimant->sex = $validated['sex_c'];
            $claimant->contact_num = $validated['contact_num_c'];

            $claimant->save();

            // Get Next Auto Increment
            $statement = DB::select("SHOW TABLE STATUS LIKE 'beneficiaries'");
            $beneficiary_id = $statement[0]->Auto_increment;
            $beneficiaries_ids = "";
            
            // Save Request Data (Member's Beneficiaries Information)

            if($validated['fname_b1'] != ""){
                $beneficiary_1 = new Beneficiary();

                $beneficiary_1->fname = $validated['fname_b1'];
                $beneficiary_1->mname = $validated['mname_b1'];
                $beneficiary_1->lname = $validated['lname_b1'];
                $beneficiary_1->ext = $validated['ext_b1'];
                $beneficiary_1->birthdate = $validated['birthdate_b1'];
                $beneficiary_1->relationship = $validated['relationship_b1'];
                $beneficiary_1->sex = $validated['sex_b1'];
                $beneficiary_1->contact_num = $validated['contact_num_b1']; 

                $beneficiary_1->save();
                $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id;
            }

            if($validated['fname_b2'] != ""){
                $beneficiary_2 = new Beneficiary();

                $beneficiary_2->fname = $validated['fname_b2'];
                $beneficiary_2->mname = $validated['mname_b2'];
                $beneficiary_2->lname = $validated['lname_b2'];
                $beneficiary_2->ext = $validated['ext_b2'];
                $beneficiary_2->birthdate = $validated['birthdate_b2'];
                $beneficiary_2->relationship = $validated['relationship_b2'];
                $beneficiary_2->sex = $validated['sex_b2'];
                $beneficiary_2->contact_num = $validated['contact_num_b2']; 

                $beneficiary_2->save();
                $beneficiaries_ids = $beneficiaries_ids . "," . (string)((int)$beneficiary_id + 1);
            }

            // Save Request Data (Members_Program)
            $memberProgram = new MembersProgram();
            $memberProgram->app_no = "0000";
            $memberProgram->user_id = $my_user->id;
            $memberProgram->member_id = $member_id;
            $memberProgram->program_id = $validated['program_id'];
            $memberProgram->branch_id = $validated['branch_id'];
            $memberProgram->claimants_id = $claimants_id;
            $memberProgram->beneficiaries_ids = $beneficiaries_ids;
            $memberProgram->registration_fee = $validated['registration_fee'];
            $memberProgram->contact_person = $validated['contact_person'];
            $memberProgram->contact_person_num = $validated['contact_person_num'];
            $memberProgram->status = "active";

            $memberProgram->save();

            // Save Request Data (Entry)

            if($validated['registration_fee'] != ""){
                $entry = new Entry();

                $entry->branch_id = $validated['branch_id'];;
                $entry->marketting_agent = auth()->id();
                $entry->member_id = $member_id;
                $entry->or_number = $validated["or_num"];
                $entry->amount = $validated['registration_fee'];
                $entry->number_of_payment = 1;
                $entry->program_id = $validated['program_id'];
                $entry->is_reactivated = 0;
                $entry->is_transferred = 0;
                $entry->remarks = "REGISTRATION";

                $entry->save();
            }

            // Back to View
            return redirect('/members')->with("success_msg", $member->lname." Member Created Successfully");

        } else {
            return redirect('/');
        }
    }

    public function update(Request $request, $id){
        //code
    }

    public function destroy(Request $request){
        if(auth()->check()){

            $memberProgram = DB::table('members_program')->where('id', $request->input("id"))->get();

            // Destroy Request Data
            Member::where("id", $request->input("id"))->delete();
            MembersProgram::where("id", $request->input("id"))->delete();
            
            foreach($memberProgram as $mp){
                Claimant::where("id", $mp->claimants_id)->delete();
                if($mp->beneficiaries_ids != ""){
                    $temp = explode(",", $mp->beneficiaries_ids);
                    foreach($temp as $t){
                        Beneficiary::where("id", (int)$t)->delete();
                    }
                }

            }

            return redirect('/members')->with("success_msg", "Deleted Successfully");

        } else {
            return redirect('/');
        }
    }

}
