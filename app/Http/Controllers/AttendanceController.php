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

    /**
     * Defeault Page for Attendance Module
     */
    public function index()
    {
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

    /**
     * Inserts Attendance Record (Time In)
     */
    public function store(Request $request)
    {
        if(auth()->check()){

            $my_user = auth()->user();

            // Save Request Data
            $contents = new Attendance();

            $contents->user_id = $my_user->id;

            // Put Current Date and Time in PHT Timezone
            $contents->time_in = now()->format('Y-m-d H:i:s');

            $contents->save();

            // Back to View
            return redirect('/attendance')->with("success_msg", "Attendance Recorded");

        } else {
            return redirect('/');
        }
    }

    /**
     * Updates Attendance Record (Time Out)
     */
    public function update(Request $request, $id)
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $record = DB::table('attendance')->where('time_out', null)->where('user_id', $my_user->id)->get();

            // Save Request Data
            $contents = Attendance::find($record[0]->id);
            $contents->time_out = now()->format('Y-m-d H:i:s');
            $contents->save();

            // Back to View
            return redirect('/attendance')->with("success_msg", "Attendance Recorded");

        } else {
            return redirect('/');
        }
    }

    /**
     * Admin Override: Update any attendance record's time_in and/or time_out.
     * Only accessible to usertype == 1 (admin).
     */
    public function adminUpdate(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        // Only admins can use this
        if (auth()->user()->usertype != 1) {
            abort(403, 'Unauthorized.');
        }
 
        $validated = $request->validate([
            'time_in'  => ['nullable', 'date_format:Y-m-d\TH:i'],  // datetime-local input format
            'time_out' => ['nullable', 'date_format:Y-m-d\TH:i', 'after_or_equal:time_in'],
        ]);
 
        $attendance = Attendance::findOrFail($id);
 
        // Convert from datetime-local format (Y-m-dTH:i) to MySQL timestamp format
        $attendance->time_in  = $validated['time_in']
            ? date('Y-m-d H:i:s', strtotime($validated['time_in']))
            : $attendance->time_in;
 
        $attendance->time_out = isset($validated['time_out']) && $validated['time_out'] !== null
            ? date('Y-m-d H:i:s', strtotime($validated['time_out']))
            : null;
 
        $attendance->save();
 
        return redirect('/attendance')->with('success_msg', 'Attendance record updated successfully.');
    }

    /**
     * Deletes Attendance Record
     */
    public function destroy(Request $request)
    {
        if(auth()->check()){

            // Destroy Request Data
            Branch::where("id", $request->input("id"))->delete();
            return redirect('/branch')->with("success_msg", "Deleted Successfully");

        } else {
            return redirect('/');
        }
    }

}
