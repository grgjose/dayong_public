<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $branches = DB::table('branches')->orderBy('id')->get();
            $reports = DB::table('reports')->orderBy('id')->get();

            return view('main', [
                'my_user' => $my_user,
                'branches' => $branches,
                'reports' => $reports,
            ])
            ->with('header_title', 'Reports')
            ->with('subview', 'dashboard-contents.modules.reports');

        } else {
            return redirect('/');
        }
    }

    public function generate(Request $request)
    {

        // Get Request Data
        $validated = $request->validate([
            "type" => ['required'],
            "branch" => ['required'],
        ]);

        $my_user = auth()->user();
        $branches = DB::table('branches')->orderBy('id')->get();
        $users = DB::table('users')->orderBy('id')->get();
        $name = '';
        $pdf = new PDF();
        $filename = '';

        foreach($branches as $branch){
            if($branch->id == $validated['branch']){
                $name = $branch->branch;
                break;
            }
        }

        if($validated["type"] == "daily"){

            // New Sales
            $new_sales = DB::table('members')->where('created_at', '<', date('Y-m-d'));

            // Collection
            $collection = DB::table('entries')->where('created_at', '<', date('Y-m-d'));


            
            $results_col = DB::table('users')
            ->join('entries', 'users.id', '=', 'entries.marketting_agent')
            ->join('members', 'entries.member_id', '=', 'members.id')
            ->select([
                'users.lname as user_name',
                DB::raw('COUNT(DISTINCT members.fname) as number_of_accounts'),
                DB::raw('SUM(entries.amount) as total_amount'),
                DB::raw('SUM(entries.incentives_total) as total_incentives'),
                DB::raw('SUM(entries.net) as total_net'),
                DB::raw('SUM(entries.fidelity_total) as total_fidelity'),
                DB::raw('MIN(entries.created_at) as created_at')
            ])
            ->groupBy('users.id', 'users.lname')
            ->get();
    
            $results_ns = DB::table('users')
            ->join('members_program', 'users.id', '=', 'members_program.agent_id')
            ->join('members', 'members_program.member_id', '=', 'members.id')
            ->select([
                'users.lname as user_name',
                DB::raw('COUNT(DISTINCT members.fname) as number_of_accounts'),
                DB::raw('SUM(members_program.amount) as total_amount'),
                DB::raw('SUM(members_program.incentives_total) as total_incentives'),
                DB::raw('SUM(members_program.net) as total_net'),
                DB::raw('SUM(members_program.fidelity_total) as total_fidelity'),
                DB::raw('MIN(members_program.created_at) as created_at')
            ])
            ->groupBy('users.id', 'users.lname')
            ->get();

            $filename = 'daily_report_'. date('m_d_Y') .'.pdf';

            $pdf = Pdf::loadView('forms.daily_report', [
                'branches' => $branches,
                'users' => $users,
                'monthAndYear' => date('F Y'),
                'date' => date('m/d/Y'),
                'branch' => $name,
                'cashier' => $my_user->lname.' '.$my_user->fname,
                'results_col' => $results_col,
                'results_ns' => $results_ns,
                'ns_result' => array(),
                'col_result' => array(),
            ]);

            $content = $pdf->download()->getOriginalContent();
            Storage::put('public/daily/'.$filename,$content);

        } else if($validated["type"] == "weekly"){

            
        } else if($validated["type"] == "monthly"){

        }

        return $pdf->download($filename);
    }

    public function store(Request $request){
    }

    public function update(Request $request){
        
    }

    public function destroy(Request $request){
        
    }
}
