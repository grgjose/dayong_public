{{-- resources/views/dashboard-contents/modules/cashflow.blade.php --}}

<style>
    .modal { overflow-y: auto !important; }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    .nav-tabs .nav-link.active { font-weight: bold; }
    .table td, .table th { vertical-align: middle; }
</style>

<section class="content">
    <div class="container-fluid">

        {{-- ── Flash Messages ── --}}
        @if(session('success_msg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success_msg') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error_msg'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error_msg') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- ── Page Tabs ── --}}
        <ul class="nav nav-tabs mb-3" id="cfTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="remittance-tab" data-toggle="tab" href="#tab-remittance" role="tab">
                    <i class="fas fa-money-bill-wave"></i> Cash Remittances
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="expense-tab" data-toggle="tab" href="#tab-expense" role="tab">
                    <i class="fas fa-receipt"></i> Expenses / Cash Out
                </a>
            </li>
        </ul>

        <div class="tab-content" id="cfTabContent">

            {{-- ================================================================
                 TAB 1 — CASH REMITTANCES
            ================================================================ --}}
            <div class="tab-pane fade show active" id="tab-remittance" role="tabpanel">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top:6px;">Cash Remittances</h3>
                        @if($my_user->usertype != 3)
                            <button class="btn btn-success float-right" data-toggle="modal" data-target="#AddRemittanceModal">
                                <span class="fas fa-plus"></span> Add Remittance
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <table id="remittanceTable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Branch</th>
                                    <th>MAS</th>
                                    <th>Type</th>
                                    <th>Bank / GCash No.</th>
                                    <th>Reference No.</th>
                                    <th>Amount</th>
                                    <th>Encoded By</th>
                                    <th>Remarks</th>
                                    <th>Attachments</th>
                                    @if($my_user->usertype != 3)
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($remittances as $r)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('Y-m-d', strtotime($r->transaction_date)) }}</td>
                                        <td>
                                            @foreach($branches as $b)
                                                @if($b->id == $r->branch_id) {{ strtoupper($b->branch) }} @break @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($r->mas_id)
                                                @foreach($users as $u)
                                                    @if($u->id == $r->mas_id) {{ strtoupper($u->fname.' '.$u->lname) }} @break @endif
                                                @endforeach
                                            @else
                                                {{ strtoupper($r->mas_name ?? '—') }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $r->transaction_type == 'bank' ? 'badge-primary' : 'badge-info' }}">
                                                {{ strtoupper($r->transaction_type) }}
                                            </span>
                                        </td>
                                        <td>{{ $r->bank_name ?? $r->gcash_number ?? '—' }}</td>
                                        <td>{{ $r->reference_number ?? '—' }}</td>
                                        <td>₱ {{ number_format($r->amount, 2) }}</td>
                                        <td>
                                            @foreach($users as $u)
                                                @if($u->id == $r->encoder_id) {{ strtoupper($u->fname.' '.$u->lname) }} @break @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $r->remarks ?? '—' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="openAttachmentModal('remittance', {{ $r->id }}, {{ $r->attachments->count() }})"
                                                data-toggle="modal"
                                                data-target="#AttachmentModal">
                                                <span class="fas fa-paperclip"></span>
                                                @if($r->attachments->count() > 0)
                                                    <span class="badge badge-info">{{ $r->attachments->count() }}</span>
                                                @endif
                                            </button>
                                        </td>
                                        @if($my_user->usertype != 3)
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    data-toggle="modal"
                                                    data-target="#EditRemittanceModal"
                                                    onclick="remittanceEditFunction(
                                                        {{ $r->id }},
                                                        '{{ $r->branch_id }}',
                                                        '{{ $r->mas_id ?? '' }}',
                                                        '{{ addslashes($r->mas_name ?? '') }}',
                                                        '{{ $r->transaction_type }}',
                                                        '{{ $r->amount }}',
                                                        '{{ addslashes($r->bank_name ?? '') }}',
                                                        '{{ $r->gcash_number ?? '' }}',
                                                        '{{ $r->reference_number ?? '' }}',
                                                        '{{ $r->transaction_date }}',
                                                        '{{ addslashes($r->remarks ?? '') }}'
                                                    )">
                                                    <span class="fas fa-pen"></span>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    data-toggle="modal"
                                                    data-target="#DeleteRemittanceModal"
                                                    onclick="remittanceDeleteFunction({{ $r->id }})">
                                                    <span class="fas fa-trash"></span>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ================================================================
                 TAB 2 — EXPENSES / CASH OUT
            ================================================================ --}}
            <div class="tab-pane fade" id="tab-expense" role="tabpanel">
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title" style="padding-top:6px;">Expenses / Cash Out</h3>
                        @if($my_user->usertype != 3)
                            <button class="btn btn-success float-right" data-toggle="modal" data-target="#AddExpenseModal">
                                <span class="fas fa-plus"></span> Add Expense
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <table id="expenseTable" class="table table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Branch</th>
                                    <th>Type of Expense</th>
                                    <th>Member (Burial)</th>
                                    <th>MAS</th>
                                    <th>Receipt #</th>
                                    <th>Amount</th>
                                    <th>Encoded By</th>
                                    <th>Remarks</th>
                                    <th>Attachments</th>
                                    @if($my_user->usertype != 3)
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $e)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('Y-m-d', strtotime($e->transaction_date)) }}</td>
                                        <td>
                                            @foreach($branches as $b)
                                                @if($b->id == $e->branch_id) {{ strtoupper($b->branch) }} @break @endif
                                            @endforeach
                                        </td>
                                        <td>{{ strtoupper($e->type_of_expense) }}</td>
                                        <td>
                                            @if($e->member_id)
                                                @foreach($members as $m)
                                                    @if($m->id == $e->member_id)
                                                        {{ strtoupper($m->lname.', '.$m->fname) }} @break
                                                    @endif
                                                @endforeach
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if($e->mas_id)
                                                @foreach($users as $u)
                                                    @if($u->id == $e->mas_id) {{ strtoupper($u->fname.' '.$u->lname) }} @break @endif
                                                @endforeach
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $e->receipt_number ?? '—' }}</td>
                                        <td>₱ {{ number_format($e->amount, 2) }}</td>
                                        <td>
                                            @foreach($users as $u)
                                                @if($u->id == $e->encoder_id) {{ strtoupper($u->fname.' '.$u->lname) }} @break @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $e->remarks ?? '—' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="openAttachmentModal('expense', {{ $e->id }}, {{ $e->attachments->count() }})"
                                                data-toggle="modal"
                                                data-target="#AttachmentModal">
                                                <span class="fas fa-paperclip"></span>
                                                @if($e->attachments->count() > 0)
                                                    <span class="badge badge-danger">{{ $e->attachments->count() }}</span>
                                                @endif
                                            </button>
                                        </td>
                                        @if($my_user->usertype != 3)
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    data-toggle="modal"
                                                    data-target="#EditExpenseModal"
                                                    onclick="expenseEditFunction(
                                                        {{ $e->id }},
                                                        '{{ $e->branch_id }}',
                                                        '{{ $e->mas_id ?? '' }}',
                                                        '{{ $e->member_id ?? '' }}',
                                                        '{{ addslashes($e->type_of_expense) }}',
                                                        '{{ $e->receipt_number ?? '' }}',
                                                        '{{ $e->amount }}',
                                                        '{{ $e->transaction_date }}',
                                                        '{{ addslashes($e->remarks ?? '') }}'
                                                    )">
                                                    <span class="fas fa-pen"></span>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    data-toggle="modal"
                                                    data-target="#DeleteExpenseModal"
                                                    onclick="expenseDeleteFunction({{ $e->id }})">
                                                    <span class="fas fa-trash"></span>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>{{-- end tab-content --}}
    </div>
</section>


{{-- ====================================================================
     MODAL: ADD REMITTANCE
==================================================================== --}}
<div class="modal fade" id="AddRemittanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white">Add Cash Remittance</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="/expenses/remittance/store" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Branch <span class="text-danger">*</span></label>
                            <select class="form-control chosen-select" name="branch_id" required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ strtoupper($b->branch) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="transaction_date" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>MAS (Agent)</label>
                            <select class="form-control chosen-select" name="mas_id" id="add_rem_mas_id">
                                <option value="">-- Select MAS or type name below --</option>
                                @foreach($users as $u)
                                    @if($u->usertype == 3)
                                        <option value="{{ $u->id }}">{{ strtoupper($u->fname.' '.$u->lname) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>MAS Name (if not in list)</label>
                            <input type="text" class="form-control" name="mas_name" placeholder="e.g. JUAN DELA CRUZ">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Transaction Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="transaction_type" id="add_rem_type" onchange="toggleRemittanceFields('add')" required>
                                <option value="bank">Bank Transfer</option>
                                <option value="gcash">GCash</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4" id="add_bank_name_group">
                            <label>Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" placeholder="e.g. RCBC, BDO">
                        </div>
                        <div class="form-group col-md-4" id="add_gcash_number_group" style="display:none;">
                            <label>GCash Number</label>
                            <input type="text" class="form-control" name="gcash_number" placeholder="e.g. 09XXXXXXXXX">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Reference Number</label>
                            <input type="text" class="form-control" name="reference_number" placeholder="Reference / Transaction No.">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="amount" required min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" class="form-control" name="remarks">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info text-white">Save Remittance</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ====================================================================
     MODAL: EDIT REMITTANCE
==================================================================== --}}
<div class="modal fade" id="EditRemittanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Edit Cash Remittance</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editRemittanceForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Branch <span class="text-danger">*</span></label>
                            <select class="form-control chosen-select" id="edit_rem_branch_id" name="branch_id" required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ strtoupper($b->branch) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_rem_date" name="transaction_date" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>MAS (Agent)</label>
                            <select class="form-control chosen-select" id="edit_rem_mas_id" name="mas_id">
                                <option value="">-- Select MAS or type name below --</option>
                                @foreach($users as $u)
                                    @if($u->usertype == 3)
                                        <option value="{{ $u->id }}">{{ strtoupper($u->fname.' '.$u->lname) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>MAS Name (if not in list)</label>
                            <input type="text" class="form-control" id="edit_rem_mas_name" name="mas_name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Transaction Type <span class="text-danger">*</span></label>
                            <select class="form-control chosen-select" id="edit_rem_type" name="transaction_type" onchange="toggleRemittanceFields('edit')" required>
                                <option value="bank">Bank Transfer</option>
                                <option value="gcash">GCash</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4" id="edit_bank_name_group">
                            <label>Bank Name</label>
                            <input type="text" class="form-control" id="edit_rem_bank_name" name="bank_name">
                        </div>
                        <div class="form-group col-md-4" id="edit_gcash_number_group" style="display:none;">
                            <label>GCash Number</label>
                            <input type="text" class="form-control" id="edit_rem_gcash_number" name="gcash_number">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Reference Number</label>
                            <input type="text" class="form-control" id="edit_rem_reference" name="reference_number">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="edit_rem_amount" name="amount" required min="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" class="form-control" id="edit_rem_remarks" name="remarks">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Remittance</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ====================================================================
     MODAL: DELETE REMITTANCE
==================================================================== --}}
<div class="modal fade" id="DeleteRemittanceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Delete Remittance</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="deleteRemittanceForm" action="/expenses/remittance/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>Are you sure you want to delete this remittance record?</h6>
                    <input type="hidden" id="delete_rem_id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ====================================================================
     MODAL: ADD EXPENSE
==================================================================== --}}
<div class="modal fade" id="AddExpenseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Add Expense / Cash Out</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="/expenses/expense/store" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Branch <span class="text-danger">*</span></label>
                            <select class="form-control chosen-select" name="branch_id" required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ strtoupper($b->branch) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="transaction_date" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Type of Expense <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="type_of_expense" required placeholder="e.g. BURIAL ASSISTANCE, UTILITIES">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="amount" required min="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>MAS (Agent) <small class="text-muted">— optional</small></label>
                            <select class="form-control chosen-select" name="mas_id">
                                <option value="">-- None --</option>
                                @foreach($users as $u)
                                    @if($u->usertype == 3)
                                        <option value="{{ $u->id }}">{{ strtoupper($u->fname.' '.$u->lname) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Member (Burial Assistance) <small class="text-muted">— optional</small></label>
                            <select class="form-control chosen-select" name="member_id">
                                <option value="">-- None --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">{{ strtoupper($m->lname.', '.$m->fname) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Receipt #</label>
                            <input type="text" class="form-control" name="receipt_number" placeholder="OR / Receipt Number">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Remarks</label>
                            <input type="text" class="form-control" name="remarks">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Save Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ====================================================================
     MODAL: EDIT EXPENSE
==================================================================== --}}
<div class="modal fade" id="EditExpenseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Edit Expense / Cash Out</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editExpenseForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Branch <span class="text-danger">*</span></label>
                            <select class="form-control chosen-select" id="edit_exp_branch_id" name="branch_id" required>
                                <option value="">-- Select Branch --</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ strtoupper($b->branch) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_exp_date" name="transaction_date" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Type of Expense <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_exp_type" name="type_of_expense" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" id="edit_exp_amount" name="amount" required min="0">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>MAS (Agent) <small class="text-muted">— optional</small></label>
                            <select class="form-control chosen-select" id="edit_exp_mas_id" name="mas_id">
                                <option value="">-- None --</option>
                                @foreach($users as $u)
                                    @if($u->usertype == 3)
                                        <option value="{{ $u->id }}">{{ strtoupper($u->fname.' '.$u->lname) }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Member (Burial Assistance) <small class="text-muted">— optional</small></label>
                            <select class="form-control chosen-select" id="edit_exp_member_id" name="member_id">
                                <option value="">-- None --</option>
                                @foreach($members as $m)
                                    <option value="{{ $m->id }}">{{ strtoupper($m->lname.', '.$m->fname) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Receipt #</label>
                            <input type="text" class="form-control" id="edit_exp_receipt" name="receipt_number">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Remarks</label>
                            <input type="text" class="form-control" id="edit_exp_remarks" name="remarks">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ====================================================================
     MODAL: DELETE EXPENSE
==================================================================== --}}
<div class="modal fade" id="DeleteExpenseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Delete Expense</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="deleteExpenseForm" action="/expenses/expense/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <h6>Are you sure you want to delete this expense record?</h6>
                    <input type="hidden" id="delete_exp_id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ====================================================================
     MODAL: ATTACHMENTS
     Used for both Remittances and Expenses.
     The JS function openAttachmentModal() populates it dynamically.
==================================================================== --}}
<div class="modal fade" id="AttachmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title text-white">
                    <span class="fas fa-paperclip"></span> Attachments
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
 
                {{-- Upload Form --}}
                <form id="attachmentUploadForm" action="/expenses/attachment/store" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- These hidden inputs are set by JS when the modal opens --}}
                    <input type="hidden" id="att_attachable_type" name="attachable_type">
                    <input type="hidden" id="att_attachable_id"   name="attachable_id">
 
                    <div class="form-group">
                        <label><strong>Upload New File(s)</strong> <small class="text-muted">(max 10 MB each)</small></label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file"
                                    class="custom-file-input"
                                    id="att_files"
                                    name="files[]"
                                    multiple
                                    accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                                <label class="custom-file-label" for="att_files">Choose file(s)...</label>
                            </div>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary">
                                    <span class="fas fa-upload"></span> Upload
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Accepted: images, PDF, Word, Excel, ZIP/RAR</small>
                    </div>
                </form>
 
                <hr>
 
                {{-- Existing Attachments List --}}
                <h6 class="mb-2"><strong>Existing Attachments</strong></h6>
                <div id="attachmentList">
                    {{-- Populated dynamically by JS based on attachable_type + attachable_id --}}
                    <p class="text-muted" id="noAttachmentsMsg">No attachments yet.</p>
                </div>
 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ====================================================================
     MODAL: DELETE ATTACHMENT (inline confirmation)
==================================================================== --}}
<div class="modal fade" id="DeleteAttachmentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Delete Attachment</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="deleteAttachmentForm" action="/expenses/attachment/destroy" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="del_att_name"></strong>?</p>
                    <input type="hidden" id="del_att_id" name="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
 
{{--
─────────────────────────────────────────────────────────────────────────────────
SECTION F — JavaScript additions
ADD these functions inside the <script> block (or in scripts.blade.php
wherever the cashflow JS lives now).
─────────────────────────────────────────────────────────────────────────────────
--}}

<script>
    // ── Attachment data passed from Blade (keyed by type:id) ────────────────────
    // We build a JS lookup object from the server-rendered data so we can
    // populate the attachment list without an AJAX call.
    var attachmentData = {
        @foreach($remittances as $r)
            'remittance:{{ $r->id }}': [
                @foreach($r->attachments as $att)
                {
                    id: {{ $att->id }},
                    name: '{{ addslashes($att->original_name) }}',
                    url: '{{ url("/expenses/attachment/".$att->id) }}'
                },
                @endforeach
            ],
        @endforeach
        @foreach($expenses as $e)
            'expense:{{ $e->id }}': [
                @foreach($e->attachments as $att)
                {
                    id: {{ $att->id }},
                    name: '{{ addslashes($att->original_name) }}',
                    url: '{{ url("/expenses/attachment/".$att->id) }}'
                },
                @endforeach
            ],
        @endforeach
    };
    
    // ── Open Attachment Modal ────────────────────────────────────────────────────
    function openAttachmentModal(type, id, count) {
        // Set hidden inputs for the upload form
        $('#att_attachable_type').val(type);
        $('#att_attachable_id').val(id);
    
        // Render the existing attachments list
        var key   = type + ':' + id;
        var files = attachmentData[key] || [];
        var list  = $('#attachmentList');
        list.empty();
    
        if (files.length === 0) {
            list.html('<p class="text-muted">No attachments yet.</p>');
        } else {
            var html = '<ul class="list-group">';
            files.forEach(function(f) {
                html += '<li class="list-group-item d-flex justify-content-between align-items-center">'
                    +   '<span><span class="fas fa-file mr-2 text-muted"></span>'
                    +     '<a href="' + f.url + '" target="_blank">' + f.name + '</a>'
                    +   '</span>'
                    +   '<button class="btn btn-sm btn-outline-danger" '
                    +     'onclick="confirmDeleteAttachment(' + f.id + ', \'' + f.name.replace(/'/g, "\\'") + '\')"'
                    +     ' data-toggle="modal" data-target="#DeleteAttachmentModal">'
                    +     '<span class="fas fa-trash"></span>'
                    +   '</button>'
                    + '</li>';
            });
            html += '</ul>';
            list.html(html);
        }
    
        // Reset the file input label
        $('#att_files').val('');
        $('.custom-file-label').text('Choose file(s)...');
    }
    
    // ── Confirm Delete Attachment ────────────────────────────────────────────────
    function confirmDeleteAttachment(id, name) {
        $('#del_att_id').val(id);
        $('#del_att_name').text(name);
    }
</script>