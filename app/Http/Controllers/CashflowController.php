<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remittance;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class CashflowController extends Controller
{
    // =========================================================================
    // INDEX
    // =========================================================================
 
    public function index()
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $my_user   = auth()->user();
        $branches  = DB::table('branches')->orderBy('branch')->get();
        $users     = DB::table('users')->orderBy('id')->get();
        $members   = DB::table('members')->where('deleted_at', null)->orderBy('lname')->get();
 
        $remittances = DB::table('remittances')
            ->where('deleted_at', null)
            ->orderBy('created_at', 'desc')
            ->get();
 
        $expenses = DB::table('expenses')
            ->where('deleted_at', null)
            ->orderBy('created_at', 'desc')
            ->get();
 
        return view('main', [
            'my_user'     => $my_user,
            'branches'    => $branches,
            'users'       => $users,
            'members'     => $members,
            'remittances' => $remittances,
            'expenses'    => $expenses,
        ])
        ->with('header_title', 'Cashflow')
        ->with('subview', 'dashboard-contents.modules.cashflow');
    }
 
    // =========================================================================
    // REMITTANCES — STORE
    // =========================================================================
 
    public function storeRemittance(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $validated = $request->validate([
            'branch_id'        => ['required'],
            'mas_id'           => ['nullable'],
            'mas_name'         => ['nullable', 'string', 'max:255'],
            'transaction_type' => ['required', 'in:bank,gcash'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'bank_name'        => ['nullable', 'string', 'max:255'],
            'gcash_number'     => ['nullable', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'remarks'          => ['nullable', 'string', 'max:500'],
        ]);
 
        $my_user = auth()->user();
 
        $remittance = new Remittance();
        $remittance->branch_id        = $validated['branch_id'];
        $remittance->encoder_id       = $my_user->id;
        $remittance->mas_id           = $validated['mas_id'] ?: null;
        $remittance->mas_name         = $validated['mas_name'];
        $remittance->transaction_type = $validated['transaction_type'];
        $remittance->amount           = $validated['amount'];
        $remittance->bank_name        = $validated['bank_name'] ?? null;
        $remittance->gcash_number     = $validated['gcash_number'] ?? null;
        $remittance->reference_number = $validated['reference_number'] ?? null;
        $remittance->transaction_date = $validated['transaction_date'];
        $remittance->remarks          = $validated['remarks'];
        $remittance->save();
 
        return redirect('/expenses')->with('success_msg', 'Remittance added successfully.');
    }
 
    // =========================================================================
    // REMITTANCES — UPDATE
    // =========================================================================
 
    public function updateRemittance(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $validated = $request->validate([
            'branch_id'        => ['required'],
            'mas_id'           => ['nullable'],
            'mas_name'         => ['nullable', 'string', 'max:255'],
            'transaction_type' => ['required', 'in:bank,gcash'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'bank_name'        => ['nullable', 'string', 'max:255'],
            'gcash_number'     => ['nullable', 'string', 'max:255'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'remarks'          => ['nullable', 'string', 'max:500'],
        ]);
 
        $remittance = Remittance::findOrFail($id);
        $remittance->branch_id        = $validated['branch_id'];
        $remittance->mas_id           = $validated['mas_id'] ?: null;
        $remittance->mas_name         = $validated['mas_name'];
        $remittance->transaction_type = $validated['transaction_type'];
        $remittance->amount           = $validated['amount'];
        $remittance->bank_name        = $validated['bank_name'] ?? null;
        $remittance->gcash_number     = $validated['gcash_number'] ?? null;
        $remittance->reference_number = $validated['reference_number'] ?? null;
        $remittance->transaction_date = $validated['transaction_date'];
        $remittance->remarks          = $validated['remarks'];
        $remittance->updated_at       = now();
        $remittance->save();
 
        return redirect('/expenses')->with('success_msg', 'Remittance updated successfully.');
    }
 
    // =========================================================================
    // REMITTANCES — DESTROY
    // =========================================================================
 
    public function destroyRemittance(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $remittance = Remittance::findOrFail($request->input('id'));
        $remittance->delete();
 
        return redirect('/expenses')->with('success_msg', 'Remittance deleted successfully.');
    }
 
    // =========================================================================
    // EXPENSES — STORE
    // =========================================================================
 
    public function storeExpense(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $validated = $request->validate([
            'branch_id'       => ['required'],
            'mas_id'          => ['nullable'],
            'member_id'       => ['nullable'],
            'type_of_expense' => ['required', 'string', 'max:255'],
            'receipt_number'  => ['nullable', 'string', 'max:255'],
            'amount'          => ['required', 'numeric', 'min:0'],
            'transaction_date'=> ['required', 'date'],
            'remarks'         => ['nullable', 'string', 'max:500'],
        ]);
 
        $my_user = auth()->user();
 
        $expense = new Expense();
        $expense->branch_id        = $validated['branch_id'];
        $expense->encoder_id       = $my_user->id;
        $expense->mas_id           = $validated['mas_id'] ?: null;
        $expense->member_id        = $validated['member_id'] ?: null;
        $expense->type_of_expense  = strtoupper(trim($validated['type_of_expense']));
        $expense->receipt_number   = $validated['receipt_number'];
        $expense->amount           = $validated['amount'];
        $expense->transaction_date = $validated['transaction_date'];
        $expense->remarks          = $validated['remarks'];
        $expense->save();
 
        return redirect('/expenses')->with('success_msg', 'Expense added successfully.');
    }
 
    // =========================================================================
    // EXPENSES — UPDATE
    // =========================================================================
 
    public function updateExpense(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $validated = $request->validate([
            'branch_id'       => ['required'],
            'mas_id'          => ['nullable'],
            'member_id'       => ['nullable'],
            'type_of_expense' => ['required', 'string', 'max:255'],
            'receipt_number'  => ['nullable', 'string', 'max:255'],
            'amount'          => ['required', 'numeric', 'min:0'],
            'transaction_date'=> ['required', 'date'],
            'remarks'         => ['nullable', 'string', 'max:500'],
        ]);
 
        $expense = Expense::findOrFail($id);
        $expense->branch_id        = $validated['branch_id'];
        $expense->mas_id           = $validated['mas_id'] ?: null;
        $expense->member_id        = $validated['member_id'] ?: null;
        $expense->type_of_expense  = strtoupper(trim($validated['type_of_expense']));
        $expense->receipt_number   = $validated['receipt_number'];
        $expense->amount           = $validated['amount'];
        $expense->transaction_date = $validated['transaction_date'];
        $expense->remarks          = $validated['remarks'];
        $expense->updated_at       = now();
        $expense->save();
 
        return redirect('/expenses')->with('success_msg', 'Expense updated successfully.');
    }
 
    // =========================================================================
    // EXPENSES — DESTROY
    // =========================================================================
 
    public function destroyExpense(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $expense = Expense::findOrFail($request->input('id'));
        $expense->delete();
        $expense->save();
 
        return redirect('/expenses')->with('success_msg', 'Expense deleted successfully.');
    }
}
