<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class ProgramController extends Controller
{
    
    public function index(){
        if(auth()->check()){

            $my_user = auth()->user();
            $programs = DB::table('programs')->orderBy('id')->where('deleted_at', null)->get();

            return view('main', [
                'my_user' => $my_user,
                'programs' => $programs,
            ])
            ->with('header_title', 'Programs')
            ->with('subview', 'dashboard-contents.settings.program');

        } else {
            return redirect('/');
        }
    }

    public function store(Request $request){
        if(auth()->check()){

            // Get Request Data
            $validated = $request->validate([
                "code" => ['required', 'unique:programs'],
                "description" => ['nullable'],
                "with_beneficiaries" => ['required'],
                "age_min" => ['nullable'],
                "age_max" => ['nullable'],
                "ben_age_min" => ['nullable'],
                "ben_age_max" => ['nullable'],
            ]);

            // Save Request Data
            $contents = new Program();

            $contents->code = $validated['code'];
            $contents->description = $validated['description'];
            $contents->with_beneficiaries = ($validated['with_beneficiaries'] == "yes" ? true : false);
            $contents->age_min = $validated['age_min'];
            $contents->age_max = $validated['age_max'];
            $contents->ben_age_min = $validated['ben_age_min'];
            $contents->ben_age_max = $validated['ben_age_max'];

            $contents->save();

            // Back to View
            return redirect('/program')->with("success_msg", $contents->code." Program Created Successfully");

        } else {
            return redirect('/');
        }
    }

    public function update(Request $request, $id){
        if(auth()->check()){

            // Get Request Data
            $validated = $request->validate([
                "code" => ['required'],
                "description" => ['nullable'],
                "with_beneficiaries" => ['required'],
                "age_min" => ['nullable'],
                "age_max" => ['nullable'],
                "ben_age_min" => ['nullable'],
                "ben_age_max" => ['nullable'],
            ]);

            // Save Request Data
            $contents = Program::find($id);

            $contents->code = $validated['code'];
            $contents->description = $validated['description'];
            $contents->with_beneficiaries = ($validated['with_beneficiaries'] == "yes" ? true : false);
            $contents->age_min = $validated['age_min'];
            $contents->age_max = $validated['age_max'];
            $contents->ben_age_min = $validated['ben_age_min'];
            $contents->ben_age_max = $validated['ben_age_max'];
            $contents->updated_at = date('Y-m-d G:i:s');

            $contents->save();

            // Back to View
            return redirect('/program')->with("success_msg", $contents->code." Program Updated Successfully");

        } else {
            return redirect('/');
        }
    }

    public function destroy(Request $request){
        if(auth()->check()){

            // Destroy Request Data
            $contents = Program::find($request->id);
            $contents->delete();
            return redirect('/program')->with("success_msg", "Deleted Successfully");

        } else {
            return redirect('/');
        }
    }

}
