<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class UserController extends Controller
{

    public function index()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $users = DB::table('users')
                ->join('usertypes', 'users.usertype', '=', 'usertypes.id')
                ->select('users.*', 'usertypes.usertype as usertype_name')
                ->whereNull('users.deleted_at')
                ->orderBy('users.id')
                ->get();

            return view('main', [
                'my_user' => $my_user,
                'users' => $users,
            ])
            ->with('header_title', 'Users')
            ->with('subview', 'dashboard-contents.settings.user-accounts');

        } else {
            return redirect('/');
        }
    }

    public function profile()
    {
        if(auth()->check()){

            $my_user = auth()->user();

            return view('main', [
                'my_user' => $my_user
            ])
            ->with('header_title', 'Users')
            ->with('subview', 'dashboard-contents.settings.profile');

        } else {
            return redirect('/');
        }
    }

    public function store(Request $request)
    {
        if(auth()->check()){

            // Get Request Data
            $validated = $request->validate([
                "username" => ['required'],
                "usertype" => ['required'],
                "fname" => ['required'],
                "mname" => ['required'],
                "lname" => ['required'],
                "email" => ['required'],
                "birthdate" => ['required'],
                "contact_num" => ['required'],
                "password" => ['nullable'],
            ]);

            // Where username or email is existing and deleted
            $user = User::onlyTrashed()
                ->where(function ($query) use ($validated) {
                    $query->where('username', strtoupper(trim($validated['username'])))
                        ->orWhere('email', strtoupper(trim($validated['email'])));
                })
                ->first();

            // If existing user is found, update the existing record instead of creating a new one
            if($user){

                if($request->hasFile('profile_pic')){
                    $ext = $request->file('profile_pic')->extension();
                    $request->file('profile_pic')->storeAs('profile_pic', $user->id.".".$ext ,'public');
                    $filename = $user->id.".".$ext;
                } else {
                    $filename = "default.png";
                }

                $user->username = strtoupper(trim($validated['username']));
                $user->usertype = $validated['usertype'];
                $user->fname = strtoupper(trim($validated['fname']));
                $user->mname = strtoupper(trim($validated['mname']));
                $user->lname = strtoupper(trim($validated['lname']));
                $user->email = strtoupper(trim($validated['email']));
                $user->birthdate = $validated['birthdate'];
                $user->contact_num = $validated['contact_num'];
                $user->profile_pic = $filename;
                $user->password = Hash::make($validated['password']);
                $user->status = "ACTIVE";
                $user->save();

            } else {

                // Get Next Auto Increment
                $statement = DB::select("SHOW TABLE STATUS LIKE 'users'");
                $nextId = $statement[0]->Auto_increment;

                if($request->hasFile('profile_pic')){
                    $ext = $request->file('profile_pic')->extension();
                    $request->file('profile_pic')->storeAs('profile_pic', $nextId.".".$ext ,'public');
                    $filename = $nextId.".".$ext;
                } else {
                    $filename = "default.png";
                }

                // Save Request Data
                $user = new User();

                $user->username = strtoupper(trim($validated['username']));
                $user->usertype = $validated['usertype'];
                $user->fname = strtoupper(trim($validated['fname']));
                $user->mname = strtoupper(trim($validated['mname']));
                $user->lname = strtoupper(trim($validated['lname']));
                $user->email = strtoupper(trim($validated['email']));
                $user->birthdate = $validated['birthdate'];
                $user->contact_num = $validated['contact_num'];
                $user->profile_pic = $filename;
                $user->password = Hash::make($validated['password']);
                $user->status = "ACTIVE";

                $user->save();

            }

            // Back to View
            return redirect('/user-accounts')->with("success_msg", $user->code." User Created Successfully");

        } else {
            return redirect('/');
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->check()){

            // Get Request Data
            $validated = $request->validate([
                "username" => ['nullable'],
                "usertype" => ['nullable'],
                "fname" => ['nullable'],
                "mname" => ['nullable'],
                "lname" => ['nullable'],
                "email" => ['nullable'],
                "birthdate" => ['nullable'],
                "contact_num" => ['nullable'],
                "password" => ['nullable'],
            ]);

            // Save Request Data
            $contents = User::find($id);

            if($request->hasFile('profile_pic')){
                $ext = $request->file('profile_pic')->extension();
                $request->file('profile_pic')->storeAs('profile_pic', $id.".".$ext ,'public');
                $filename = $id.".".$ext;
                $contents->profile_pic = $filename;
            }

            $contents->username = $validated['username'];
            
            if(isset($validated['usertype'])){
                $contents->usertype = $validated['usertype'];
            }

            $contents->fname = $validated['fname'];
            $contents->mname = $validated['mname'];
            $contents->lname = $validated['lname'];
            $contents->email = $validated['email'];
            $contents->birthdate = $validated['birthdate'];
            $contents->contact_num = $validated['contact_num'];
            $contents->updated_at = date('Y-m-d G:i:s');

            if($validated['password'] != ""){
                $contents->password = $validated['password'];
            }

            $contents->save();

            // Back to View
            if(isset($validated['usertype'])){
                return redirect('/user-accounts')->with("success_msg", $contents->lname." User Updated Successfully");
            } else {
                return redirect('/profile')->with("success_msg", "Profile Updated Successfully");
            }
            

        } else {
            return redirect('/');
        }

    }

    public function destroy(Request $request)
    {
        if(auth()->check()){

            // Confirm if User Accounts have related records in other tables before deleting
            // Find in Collection and Check if the user id exists in the collection
            $userId = $request->input('id');
            $hasRelatedRecords = 
                DB::table('entries')->where('agent_id', $userId)->exists() ||
                DB::table('members_program')->where('agent_id', $userId)->exists() ||
                DB::table('members')->where('agent_id', $userId)->exists() ||
                DB::table('reports')->where('user_id', $userId)->exists();

            if ($hasRelatedRecords) {
                return redirect('/user-accounts')->with("error_msg", "Cannot delete user with related record to either Collection, New Sales, Members, or Reports. Set user to Inactive Instead.");
            }

            // Delete User Account
            $user = User::find($userId);
            $fname = $user->fname;
            $lname = $user->lname;
            $user->delete();

            return redirect('/user-accounts')->with("success_msg", $fname." ".$lname." Deleted Successfully");

        } else {
            return redirect('/');
        }
    }

    public function change(Request $request)
    {
        if(auth()->check()){
            $my_user = auth()->user();
            if($request->hasFile('profile_pic')){
                $ext = $request->file('profile_pic')->extension();
                $request->file('profile_pic')->storeAs('profile_pic', $my_user->id.".".$ext ,'public');
                $filename = $my_user->id.".".$ext;
            } else {
                $filename = "default.png";
            }

            $contents = User::find($my_user->id);
            $contents->profile_pic = $filename;
            $contents->save();

            // Back to View
            return redirect('/profile')->with("success_msg", $contents->lname." Profile Picture Updated Successfully");

        } else {
            return redirect('/');
        }
    }

    public function register(Request $request)
    {
        // Get Request Data
        $validated = $request->validate([
            "usertype" => ['required'],
            "fname" => ['required'],
            "mname" => ['required'],
            "lname" => ['required'],
            "email" => ['required'],
            "branch" => ['nullable'],
            "birthdate" => ['required'],
            "contact_num" => ['required'],
            "password" => ['nullable'],
        ]);

        // Get Next Auto Increment
        $statement = DB::select("SHOW TABLE STATUS LIKE 'users'");
        $nextId = $statement[0]->Auto_increment;
        $filename = "default.png";

        // Save Request Data
        $contents = new User();

        $contents->username = strtoupper($validated['fname'][0].$validated['lname']);
        $contents->usertype = $validated['usertype'];
        $contents->fname = strtoupper($validated['fname']);
        $contents->mname = strtoupper($validated['mname']);
        $contents->lname = strtoupper($validated['lname']);
        $contents->email = strtoupper($validated['email']);
        $contents->birthdate = $validated['birthdate'];
        $contents->contact_num = $validated['contact_num'];
        $contents->profile_pic = $filename;
        $contents->status = "PENDING";
        $contents->password = $validated['password'];

        $contents->save();

        // Back to View
        return redirect('/')->with("success_msg", "User Registered! Please wait for email confirmation.");
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required'],
        ]);

        $exists = User::where('email', strtolower($request->email))->exists();

        // $exists = Member::whereRaw('LOWER(fname) = ?', [strtolower($request->first_name)])
        //     ->whereRaw('LOWER(lname) = ?', [strtolower($request->last_name)])
        //     ->when($request->middle_name, function ($q) use ($request) {
        //         $q->whereRaw('LOWER(mname) = ?', [strtolower($request->middle_name)]);
        //     })
        //     ->exists();

        return response()->json([
            'exists'  => $exists,
            'message' => $exists 
            ? 'A User with the same email already exists.' 
            : 'Cannot find any User with the same email.',
        ]);
    }

    public function checkContactNum(Request $request)
    {
        $request->validate([
            'contact_num' => ['required'],
        ]);

        // Make it flexible to check whethere +63 prefix or 0 prefix or just the number is entered
        $contactNum = $request->contact_num;
        if (str_starts_with($contactNum, '+63')) {
            $contactNum = '0' . substr($contactNum, 3);
        } elseif (str_starts_with($contactNum, '63')) {
            $contactNum = '0' . substr($contactNum, 2);
        }

        // Check if the contact number exists in the database for all formats
        $exists = User::where('contact_num', $contactNum)->exists() 
               || User::where('contact_num', '+63' . substr($contactNum, 1))->exists() 
               || User::where('contact_num', '63' . substr($contactNum, 1))->exists()
               || User::where('contact_num', ltrim($contactNum, '0'))->exists()
               || User::where('contact_num', '+63' . ltrim($contactNum, '0'))->exists()
               || User::where('contact_num', '63' . ltrim($contactNum, '0'))->exists();
               
        // $exists = Member::whereRaw('LOWER(fname) = ?', [strtolower($request->first_name)])
        //     ->whereRaw('LOWER(lname) = ?', [strtolower($request->last_name)])
        //     ->when($request->middle_name, function ($q) use ($request) {
        //         $q->whereRaw('LOWER(mname) = ?', [strtolower($request->middle_name)]);
        //     })
        //     ->exists();

        

        return response()->json([
            'exists'  => $exists,
            'message' => $exists 
            ? 'A User with the same contact number already exists.' 
            : 'Cannot find any User with the same contact number.',
        ]);
    }

    public function approve($id)
    {
        $user = User::find($id);
        $user->status = "ACTIVE";
        $user->save();

        return redirect('/user-accounts')->with('success_msg', 'User Activated!');
    }

}
