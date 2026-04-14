<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DateTime;

class MainController extends Controller
{
    /**
     * Shows the Login Page or Dashboard if already logged in
     */
    public function index()
    {
        if(auth()->check()){
            return redirect('/dashboard');
        } else {

            $branches = DB::table('branches')->orderBy('id')->get();

            return view('forms.login', [
                'branches' => $branches,
            ]);
        }
    }

    /**
     * Show the application dashboard.
     */
    public function dashboard()
    {
        if(auth()->check()){

            $today = date('Y-m-d');
            $my_user = auth()->user();

            $collections = DB::table('entries')
            ->where('created_at', '>', $today.' 00:00:00')
            ->where('created_at', '<', $today.' 23:59:59')->get();

            $active_agents = DB::table('users')
            ->where('status', '=', 'active')
            ->count();

            $collections_today = $collections->count();
            $total_col_this_month = 0;

            foreach($collections as $col){
                if($col->remarks != "REGISTRATION"){
                    $total_col_this_month = $total_col_this_month + $col->amount;
                }
            }

            $currentYear = date('Y');

            $collectionPerMonth  = [];
            $newMembersPerMonth  = [];
            $reactivatedPerMonth = [];
            $transferredPerMonth = [];

            for ($m = 1; $m <= 12; $m++) {
                $monthStart = $currentYear . '-' . str_pad($m, 2, '0', STR_PAD_LEFT) . '-01';
                $monthEnd   = date('Y-m-t', strtotime($monthStart));

                // Collection = sum of amounts in entries, excluding REGISTRATION, for that month
                $collectionPerMonth[] = DB::table('entries')
                    ->whereDate('created_at', '>=', $monthStart)
                    ->whereDate('created_at', '<=', $monthEnd)
                    ->where('remarks', '!=', 'REGISTRATION')
                    ->sum('amount');

                // New Members = count of members_program rows created that month (no flags needed)
                $newMembersPerMonth[] = DB::table('members_program')
                    ->whereDate('created_at', '>=', $monthStart)
                    ->whereDate('created_at', '<=', $monthEnd)
                    ->count();

                // Reactivated = entries flagged as reactivated that month
                $reactivatedPerMonth[] = DB::table('entries')
                    ->whereDate('created_at', '>=', $monthStart)
                    ->whereDate('created_at', '<=', $monthEnd)
                    //->where('is_reactivated', true)
                    ->count();

                // Transferred = entries flagged as transferred that month
                $transferredPerMonth[] = DB::table('entries')
                    ->whereDate('created_at', '>=', $monthStart)
                    ->whereDate('created_at', '<=', $monthEnd)
                    //->where('is_transferred', true)
                    ->count();
            }

            $members_today = DB::table('members')
            ->where('created_at', '>', $today.' 00:00:00')
            ->where('created_at', '<', $today.' 23:59:59')
            ->count();

            $entries = DB::table('entries')
            ->where('created_at', '>', $today.' 00:00:00')
            ->where('created_at', '<', $today.' 23:59:59')
            ->get();

            $profit_today = 0;
            foreach($entries as $entry){
                $profit_today = $profit_today + (int)$entry->amount;
            }

            $currentMonth     = date('Y-m');
            $totalCollection  = DB::table('entries')->where('month_from', 'LIKE', $currentMonth.'%')->where('remarks','!=','REGISTRATION')->sum('amount');
            $totalNewMembers  = DB::table('members_program')->whereDate('created_at','>=', date('Y-m-01'))->count();
            $totalReactivated = DB::table('entries')->whereDate('created_at','>=', date('Y-m-01'))->count();
            $totalTransferred = DB::table('entries')->whereDate('created_at','>=', date('Y-m-01'))->count();

            return view('main', [
                'my_user'              => $my_user,
                'active_agents'        => $active_agents,
                'collections_today'    => $collections_today,
                'total_col_this_month' => $total_col_this_month,
                'members_today'        => $members_today,
                'profit_today'         => $profit_today,
                // Add these:
                'collectionPerMonth'   => $collectionPerMonth,
                'newMembersPerMonth'   => $newMembersPerMonth,
                'reactivatedPerMonth'  => $reactivatedPerMonth,
                'transferredPerMonth'  => $transferredPerMonth,
                'totalCollection'      => $totalCollection,
                'totalNewMembers'      => $totalNewMembers,
                'totalReactivated'     => $totalReactivated,
                'totalTransferred'     => $totalTransferred,
            ])
            ->with('header_title', 'Dashboard')
            ->with('subview', 'dashboard-contents.modules.dashboard')
            ->with('greet_icon', 'yes');
            
        } else {
            return view('forms.login');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'min:2'],
            'password' => ['required', 'string', 'min:2'],
        ]);

        $remember = $request->boolean('remember_me');

        // Detect if input is an email
        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        if (auth()->attempt([
                $field => $request->username,
                'password' => $request->password,
                'status' => 'active', // ✅ only allow active users
            ], $remember)) {

            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->with('error_msg', 'Invalid Credentials!');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required'],
        ]);

        $users = DB::table('users')->where('email', '=', $validated['email'])->get();

        if(count($users) == 0){
            return redirect('/')->with('error_msg', 'No Users found with that Email!');
        }

        $token = $this->generateGUID();

        $user = User::find($users[0]->id);
        $user->reset_token = $token;
        $user->reset_token_sent = date("Y-m-d H:i:s");
        $user->save();

        $data = [
            'name' => $user->fname.' '.$user->lname,
            'message' => 'Please use the link below for the Password Reset Page',
            'token' => $token,
        ];

        Mail::to($validated['email'])->send(new ForgotPasswordMail($data));

        return redirect('/')->with('success_msg', 'Please check your email!');
    }

    public function resetPassword($reference)
    {
        /** @var \Illuminate\Auth\SessionGuard $auth */
        $auth = auth();
        $my_user = $auth->user();
        
        // Find user directly with Eloquent (simpler and more readable)
        $user = User::where('reset_token', $reference)->first();
    
        if (!$user) {
            return redirect('/')->with('error_msg', 'Token expired or invalid');
        }
    
        // Check if token is older than 24 hours
        $tokenSentTime = Carbon::parse($user->reset_token_sent);
        if ($tokenSentTime->addHours(24)->isPast()) {
            return redirect('/')->with('error_msg', 'Token has expired. Please request a new one.');
        }

        return view('forms.reset', [
            'user' => $user,
            'my_user' => $my_user,
        ]);
    }

    /**
     * Perform Change Password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::find($validated['user_id']);

        if (!$user) {
            // Handle the case where the user doesn't exist
            return redirect('/')->with('error_msg', 'User not found!');
        }

        $user->password = Hash::make($validated['password']);
        $user->save();
        
        return redirect('/')->with('success_msg', 'Password Updated!');

    }

    public function profile()
    {
        if(auth()->check()){

            $my_user = auth()->user();
            $members = DB::table('members')->orderBy('id')->get();
            $programs = DB::table('programs')->orderBy('id')->get();
            $branches = DB::table('branches')->orderBy('id')->get();
            $entries = DB::table('entries')->orderBy('id')->get();
            $users = DB::table('users')->orderBy('id')->get();

            return view('main', [
                'my_user' => $my_user,
                'members' => $members,
                'programs' => $programs,
                'branches' => $branches,
                'entries' => $entries,
                'users' => $users
            ])
            ->with('header_title', 'My Profile')
            ->with('subview', 'dashboard-contents.modules.profile');

        } else {
            return redirect('/');
        }
    }

    /**
     * Generates a GUID
     */
    public function generateGUID() 
    {
        if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        }
    
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0x0FFF) | 0x4000, // 4XXX - Version 4 UUID
            mt_rand(0, 0x3FFF) | 0x8000, // 8XXX, 9XXX, AXXX, or BXXX - Variant 1 UUID
            mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF), mt_rand(0, 0xFFFF)
        );
    }


}
