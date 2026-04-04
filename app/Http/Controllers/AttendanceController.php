<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class AttendanceController extends Controller
{

    public function index(){
        if(auth()->check()){

            $my_user = auth()->user();
            $attendance = DB::table('attendance')->orderBy('user_id', 'asc')->get();
            $existing = count(DB::table('attendance')->where('time_out', null)->where('user_id', $my_user->id)->get());
            $users = DB::table('users')->get();

            //dd($existing);

            return view('main', [
                'my_user' => $my_user,
                'users' => $users,
                'attendance' => $attendance,
                'existing' => $existing
            ])
            ->with('header_title', 'Attendance')
            ->with('subview', 'dashboard-contents.modules.attendance');

        } else {
            return redirect('/');
        }
    }

    public function store(Request $request){
        if(auth()->check()){

            $my_user = auth()->user();

            // Save Request Data
            $contents = new Attendance();

            $contents->user_id = $my_user->id;
            $contents->time_in = date('Y-m-d H:i:s');

            $contents->save();

            // Back to View
            return redirect('/attendance')->with("success_msg", "Attendance Recorded");

        } else {
            return redirect('/');
        }
    }

    public function update(Request $request, $id){
        if(auth()->check()){

            $my_user = auth()->user();
            $record = DB::table('attendance')->where('time_out', null)->where('user_id', $my_user->id)->get();

            // Save Request Data
            $contents = Attendance::find($record[0]->id);
            $contents->time_out = date('Y-m-d H:i:s');
            $contents->save();

            // Back to View
            return redirect('/attendance')->with("success_msg", "Attendance Recorded");

        } else {
            return redirect('/');
        }
    }

    public function destroy(Request $request){
        if(auth()->check()){

            // Destroy Request Data
            Branch::where("id", $request->input("id"))->delete();
            return redirect('/branch')->with("success_msg", "Deleted Successfully");

        } else {
            return redirect('/');
        }
    }

}
