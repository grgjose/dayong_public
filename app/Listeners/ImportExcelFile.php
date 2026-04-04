<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Models\User;
use App\Models\Entry;
use App\Models\Member;
use App\Models\Claimant;
use App\Models\Beneficiary;
use App\Models\MembersProgram;
use PHPUnit\Logging\Exception;

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

class ImportExcelFile
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $array = $event->array;
        $programs = DB::table('programs')->orderBy('code')->get();
        $branches = DB::table('branches')->orderBy('branch')->get();

        foreach($array as $sheet){

            for ($row = 1; $row < sizeOf($sheet); $row++) {

                if(trim($sheet[$row][TIMESTAMP]) != ""){

                    // Check if Marketting Agent in the User List
                    // If not, Create Default User for Agent
                    $name = ucwords(strtolower(trim($sheet[$row][MARKETTING_AGENT])), " .");
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

                    $name = ucwords(strtolower(trim($sheet[$row][PHMEMBER])), " .");
                    if(strpos($name, ",") > 0){
                        $tmp = explode(",", $name);
                        $member->fname = ucwords($tmp[1]);
                        $member->lname = ucwords($tmp[0]);
                    } else {
                        $member->fname = substr($name, strpos($name, " ") + 1);
                        $member->lname = substr($name, 0, strpos($name, " "));
                    }

                    $members = DB::table('members')->where('lname', $member->lname)
                    ->where('fname', 'LIKE', $member->fname)->get();

                    if(count($members) == 0){

                        if(trim($sheet[$row][BIRTHDATE]) != ""){
                            $birthdate = $this->excelTimestampToString(trim($sheet[$row][BIRTHDATE]));
                            //$member->birthdate = new DateTime($birthdate);
                            //$member->birthdate->format('Y-m-d H:i:s');
                            $member->birthdate = $birthdate;
                        }

                        $member->civil_status = ucwords(strtolower(trim($sheet[$row][CIVIL_STATUS])), " .");
                        $member->contact_num = ucwords(strtolower(trim($sheet[$row][CONTACT_NO])), " .");
                        $member->address = strval(ucwords(strtolower(trim($sheet[$row][ADDRESS])), " ."));

                        $timestamp = $this->excelTimestampToString(trim($sheet[$row][TIMESTAMP]));
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

                        $name = ucwords(strtolower(trim($sheet[$row][NAME])), " .");
                        if(strpos($name, ",") > 0){
                            $tmp = explode(",", $name);
                            $claimant->fname = ucwords($tmp[1]);
                            $claimant->lname = ucwords($tmp[0]);
                        } else {
                            $claimant->fname = substr($name, strpos($name, " ") + 1);
                            $claimant->lname = substr($name, 0, strpos($name, " "));
                        }

                        $claimant->contact_num = $sheet[$row][CONTACT_NO];

                        $claimant->save();

                        // Get Next Auto Increment
                        $statement = DB::select("SHOW TABLE STATUS LIKE 'beneficiaries'");
                        $beneficiary_id = $statement[0]->Auto_increment;
                        $beneficiaries_ids = "";

                        // Save Request Data (Member's Beneficiaries Information)

                        if(strtolower($sheet[$row][NAME1]) != ""
                        && strtolower($sheet[$row][NAME1]) != "n/a"
                        && strtolower($sheet[$row][NAME1]) != "na"
                        && strtolower($sheet[$row][NAME1]) != "none"){
                            $beneficiary_1 = new Beneficiary();

                            $name = ucwords(strtolower(trim($sheet[$row][NAME1])), " .");
                            if(strpos($name, ",") > 0){
                                $tmp = explode(",", $name);
                                $beneficiary_1->fname = ucwords($tmp[1]);
                                $beneficiary_1->lname = ucwords($tmp[0]);
                            } else {
                                $beneficiary_1->fname = substr($name, strpos($name, " ") + 1);
                                $beneficiary_1->lname = substr($name, 0, strpos($name, " "));
                            }

                            $beneficiary_1->relationship = ucwords(strtolower(trim($sheet[$row][RELATIONSHIP1])));

                            $beneficiary_1->save();
                            $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id;
                        }

                        if(strtolower($sheet[$row][NAME2]) != ""
                        && strtolower($sheet[$row][NAME2]) != "n/a"
                        && strtolower($sheet[$row][NAME2]) != "na"
                        && strtolower($sheet[$row][NAME2]) != "none"){
                            $beneficiary_2 = new Beneficiary();

                            $name = ucwords(strtolower(trim($sheet[$row][NAME2])), " .");
                            if(strpos($name, ",") > 0){
                                $tmp = explode(",", $name);
                                $beneficiary_2->fname = ucwords($tmp[1]);
                                $beneficiary_2->lname = ucwords($tmp[0]);
                            } else {
                                $beneficiary_2->fname = substr($name, strpos($name, " ") + 1);
                                $beneficiary_2->lname = substr($name, 0, strpos($name, " "));
                            }

                            $beneficiary_2->relationship = ucwords(strtolower(trim($sheet[$row][RELATIONSHIP2])));

                            $beneficiary_2->save();
                            $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id;
                        }

                        if(strtolower($sheet[$row][NAME3]) != ""
                        && strtolower($sheet[$row][NAME3]) != "n/a"
                        && strtolower($sheet[$row][NAME3]) != "na"
                        && strtolower($sheet[$row][NAME3]) != "none"){
                            $beneficiary_3 = new Beneficiary();

                            $name = ucwords(strtolower(trim($sheet[$row][NAME3])), " .");
                            if(strpos($name, ",") > 0){
                                $tmp = explode(",", $name);
                                $beneficiary_3->fname = ucwords($tmp[1]);
                                $beneficiary_3->lname = ucwords($tmp[0]);
                            } else {
                                $beneficiary_3->fname = substr($name, strpos($name, " ") + 1);
                                $beneficiary_3->lname = substr($name, 0, strpos($name, " "));
                            }

                            $beneficiary_3->relationship = ucwords(strtolower(trim($sheet[$row][RELATIONSHIP3])));

                            $beneficiary_3->save();
                            $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id;
                        }

                        if(strtolower($sheet[$row][NAME4]) != ""
                        && strtolower($sheet[$row][NAME4]) != "n/a"
                        && strtolower($sheet[$row][NAME4]) != "na"
                        && strtolower($sheet[$row][NAME4]) != "none"){
                            $beneficiary_4 = new Beneficiary();

                            $name = ucwords(strtolower(trim($sheet[$row][NAME4])), " .");
                            if(strpos($name, ",") > 0){
                                $tmp = explode(",", $name);
                                $beneficiary_4->fname = ucwords($tmp[1]);
                                $beneficiary_4->lname = ucwords($tmp[0]);
                            } else {
                                $beneficiary_4->fname = substr($name, strpos($name, " ") + 1);
                                $beneficiary_4->lname = substr($name, 0, strpos($name, " "));
                            }

                            $beneficiary_4->relationship = ucwords(strtolower(trim($sheet[$row][RELATIONSHIP4])));

                            $beneficiary_4->save();
                            $beneficiaries_ids = $beneficiaries_ids . $beneficiary_id;
                        }


                        // Save Request Data (Members_Program)
                        $memberProgram = new MembersProgram();
                        $memberProgram->app_no = $sheet[$row][APPLICATION_NO];
                        $memberProgram->user_id = $user_id;
                        $memberProgram->member_id = $member_id;

                        $program_id = 0;
                        foreach($programs as $program){
                            if(strtolower(trim($program->code)) == strtolower(trim($sheet[$row][DAYONG_PROGRAM]))){
                                $program_id = $program->id; 
                                break; break;
                            }
                        }

                        $branch_id = 0;
                        foreach($branches as $branch){
                            if(strtolower(trim($branch->branch)) == strtolower(trim($sheet[$row][BRANCH]))){
                                $branch_id = $branch->id;
                                break; break;
                            }
                        }

                        $memberProgram->program_id = $program_id; 
                        $memberProgram->branch_id = $branch_id;
                        $memberProgram->claimants_id = $claimants_id;
                        $memberProgram->beneficiaries_ids = $beneficiaries_ids;

                        if(is_numeric($sheet[$row][REGISTRATION_AMOUNT])){
                            $memberProgram->registration_fee = $sheet[$row][REGISTRATION_AMOUNT];
                        }

                        $memberProgram->contact_person = ucwords(trim($sheet[$row][NAME]), " .");
                        $memberProgram->contact_person_num = $sheet[$row][CONTACT_NO];
                        $memberProgram->status = "active";

                        $memberProgram->save();

                        // Save Request Data (Entry)

                        if((trim($sheet[$row][REGISTRATION_AMOUNT]) != "")&&(is_numeric($sheet[$row][REGISTRATION_AMOUNT]))){
                            $entry = new Entry();

                            $entry->branch_id = $branch_id;
                            $entry->marketting_agent = $user_id;
                            $entry->member_id = $member_id;
                            $entry->or_number = $sheet[$row][OR_NUMBER];
                            $entry->amount = $sheet[$row][REGISTRATION_AMOUNT];
                            $entry->number_of_payment = 1;
                            $entry->program_id = $program_id; 
                            $entry->is_reactivated = 0;
                            $entry->is_transferred = 0;
                            $entry->remarks = "REGISTRATION";

                            $entry->save();
                        }
                    }                        
                }
            }
        }

    }

    public function excelTimestampToString($excelTimestamp) {
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
}
