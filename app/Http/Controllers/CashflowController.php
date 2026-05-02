<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Remittance;
use App\Models\Expense;
use App\Models\CashflowAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
 
        $my_user  = auth()->user();
        $branches = DB::table('branches')->orderBy('branch')->get();
        $users    = DB::table('users')->orderBy('id')->get();
        $members  = DB::table('members')->where('deleted_at', null)->orderBy('lname')->get();
 
        // Load remittances with their attachments (Eloquent so morphMany works)
        $remittances = Remittance::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->with('attachments')
            ->get();
 
        // Load expenses with their attachments
        $expenses = Expense::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->with('attachments')
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
            'branch_id'        => ['required'],
            'mas_id'           => ['nullable'],
            'member_id'        => ['nullable'],
            'type_of_expense'  => ['required', 'string', 'max:255'],
            'receipt_number'   => ['nullable', 'string', 'max:255'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'remarks'          => ['nullable', 'string', 'max:500'],
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
            'branch_id'        => ['required'],
            'mas_id'           => ['nullable'],
            'member_id'        => ['nullable'],
            'type_of_expense'  => ['required', 'string', 'max:255'],
            'receipt_number'   => ['nullable', 'string', 'max:255'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'remarks'          => ['nullable', 'string', 'max:500'],
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
 
        return redirect('/expenses')->with('success_msg', 'Expense deleted successfully.');
    }
 
    // =========================================================================
    // ATTACHMENTS — STORE
    // Accepts multiple files and links them to a remittance or expense.
    // =========================================================================
 
    public function storeAttachment(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $request->validate([
            'attachable_type' => ['required', 'in:remittance,expense'],
            'attachable_id'   => ['required', 'integer'],
            'files'           => ['required', 'array', 'min:1'],
            'files.*'         => ['file', 'max:10240'], // 10 MB per file
        ]);
 
        $my_user       = auth()->user();
        $type          = $request->input('attachable_type');
        $id            = $request->input('attachable_id');
        $modelClass    = $type === 'remittance' ? Remittance::class : Expense::class;
        $morphType     = $type === 'remittance' ? 'App\Models\Remittance' : 'App\Models\Expense';
 
        // Make sure the parent record exists
        $modelClass::findOrFail($id);
 
        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            // Store in storage/app/public/cashflow_attachments/
            $path = $file->store('cashflow_attachments', 'public');
 
            CashflowAttachment::create([
                'attachable_type' => $morphType,
                'attachable_id'   => $id,
                'file_path'       => $path,
                'original_name'   => $originalName,
                'uploaded_by'     => $my_user->id,
            ]);
        }
 
        return redirect('/expenses')->with('success_msg', 'Attachment(s) uploaded successfully.');
    }
 
    // =========================================================================
    // ATTACHMENTS — DESTROY
    // Deletes one attachment record and removes the file from storage.
    // =========================================================================
 
    public function destroyAttachment(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $attachment = CashflowAttachment::findOrFail($request->input('id'));
 
        // Delete file from disk
        Storage::disk('public')->delete($attachment->file_path);
 
        // Soft-delete the record
        $attachment->delete();
 
        return redirect('/expenses')->with('success_msg', 'Attachment deleted successfully.');
    }
 
    // =========================================================================
    // ATTACHMENTS — DOWNLOAD
    // Streams the file to the browser as a download.
    // =========================================================================
 
    public function downloadAttachment($id)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
 
        $attachment = CashflowAttachment::findOrFail($id);
 
        $fullPath = Storage::disk('public')->path($attachment->file_path);
 
        if (!file_exists($fullPath)) {
            abort(404, 'File not found.');
        }
 
        return response()->download($fullPath, $attachment->original_name);
    }
}
