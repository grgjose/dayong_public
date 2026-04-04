<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class AuditController extends Controller
{
    
    public function index(){
        if(auth()->check()){

            $my_user = auth()->user();
            $entries = DB::table('entries')->orderBy('id')->get();
            $programs = DB::table('programs')->orderBy('id')->get();
            $branches = DB::table('branches')->orderBy('id')->get();

            return view('main', [
                'my_user' => $my_user,
                'entries' => $entries,
                'programs' => $programs,
                'branches' => $branches,
            ])
            ->with('header_title', 'Audit')
            ->with('subview', 'dashboard-contents.modules.audit');

        } else {
            return redirect('/');
        }
    }

    public function store(Request $request){

    }

    public function update(Request $request){
        
    }

    public function destroy(Request $request){
        
    }

}
