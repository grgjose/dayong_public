<?php

namespace App\Http\Controllers;

use App\Models\Matrix;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class MatrixController extends Controller
{
    public function index()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $programs = DB::table('programs')->orderBy('id')->get();
            $matrix = DB::table('matrix')->orderBy('program_id')->get();

            return view('main', [
                'my_user' => $my_user,
                'programs' => $programs,
                'matrix' => $matrix,
            ])
            ->with('header_title', 'Incentives Matrix')
            ->with('subview', 'dashboard-contents.settings.matrix');

        } else {
            return redirect('/');
        }
    }

    public function store(Request $request)
    {
        if(auth()->check()){

            // Get Request Data
            $validated = $request->validate([
                "program_id" => ['required'],
                "nop" => ['nullable'],
                "percentage" => ['required'],
                "reactivated" => ['nullable']
            ]);

            // Save Request Data
            $contents = new Matrix();

            $contents->program_id = $validated['program_id'];
            $contents->nop = $validated['nop'];
            $contents->percentage = $validated['percentage'];
            $contents->is_reactivated = (isset($validated['reactivated']) ? true : false);

            $contents->save();

            // Back to View
            return redirect('/matrix')->with("success_msg", $contents->code." Matrix Item Created Successfully");

        } else {
            return redirect('/');
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->check()){

            // Get Request Data
            $validated = $request->validate([
                "nop" => ['nullable'],
                "percentage" => ['required'],
                "reactivated" => ['nullable']
            ]);

            // Save Request Data
            $contents = Matrix::find($id);

            $contents->nop = $validated['nop'];
            $contents->percentage = $validated['percentage'];
            $contents->is_reactivated = (isset($validated['reactivated']) ? true : false);

            $contents->save();

            // Back to View
            return redirect('/matrix')->with("success_msg", $contents->code." Matrix Item Created Successfully");

        } else {
            return redirect('/');
        }
    }

    public function destroy(Request $request)
    {
        if(auth()->check()){

            // Destroy Request Data
            Program::where("id", $request->input("id"))->delete();
            return redirect('/program')->with("success_msg", "Deleted Successfully");

        } else {
            return redirect('/');
        }
    }

}
