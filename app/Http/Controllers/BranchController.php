<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BranchController extends Controller
{

    /**
     *  Defeault Page for Branch Module
     */
    public function index()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $branches = DB::table('branches')->orderBy('id')->where('is_deleted', false)->get();

            return view('main', [
                'my_user' => $my_user,
                'branches' => $branches,
            ])
            ->with('header_title', 'Branches')
            ->with('subview', 'dashboard-contents.settings.branch');

        } else {
            return redirect('/');
        }
    }

    /**
     *  Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->check()){

            // Get Request Data
            $validated = $request->validate([
                "code" => ['required', 'unique:branches'],
                "city" => ['required'],
                "branch" => ['required', 'min:3'],
                "address" => ['nullable'],
                "description" => ['nullable'],
            ]);

            // Save Request Data
            $contents = new Branch();

            $contents->code = $validated['code'];
            $contents->city = $validated['city'];
            $contents->branch = $validated['branch'];
            $contents->address = $validated['address'];
            $contents->description = $validated['description'];
            $contents->is_deleted = false;

            $contents->save();

            // Back to View
            return redirect('/branch')->with("success_msg", $contents->branch." Branch Created Successfully");

        } else {
            return redirect('/');
        }
    }


    /**
     *  Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if(auth()->check()){

            // Get Request Data
            $validated = $request->validate([
                "code" => ['required'],
                "city" => ['required'],
                "branch" => ['required', 'min:4'],
                "address" => ['nullable'],
                "description" => ['nullable'],
            ]);

            // Save Request Data
            $contents = Branch::find($id);

            $contents->code = $validated['code'];
            $contents->city = $validated['city'];
            $contents->branch = $validated['branch'];
            $contents->address = $validated['address'];
            $contents->description = $validated['description'];
            $contents->updated_at = date('Y-m-d G:i:s');
            $contents->is_deleted = false;

            $contents->save();

            // Back to View
            return redirect('/branch')->with("success_msg", $validated['branch']." Branch Updated Successfully");

        } else {
            return redirect('/');
        }
    }

    /**
     *  Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(auth()->check()){

            // Destroy Request Data
            $contents = Branch::find($request->id);
            $contents->is_deleted = true;
            $contents->save();
            return redirect('/branch')->with("success_msg", "Deleted Successfully");

        } else {
            return redirect('/');
        }
    }

}
