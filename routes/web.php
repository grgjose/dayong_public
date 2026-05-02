<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\ExcelCollectionController;
use App\Http\Controllers\ExcelNewSalesController;
use App\Http\Controllers\FidelityController;
use App\Http\Controllers\NewSalesController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\MatrixController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CashflowController;
use App\Http\Middleware\ValidateOfficeHours;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [MainController::class, 'index']);
Route::post('/login', [MainController::class, 'login']);
Route::post('/logout', [MainController::class, 'logout']);
Route::get('/dashboard', [MainController::class, 'dashboard'])->middleware(ValidateOfficeHours::class);
Route::get('/profile', [MainController::class, 'profile'])->middleware(ValidateOfficeHours::class);
Route::post('/forgot-password', [MainController::class, 'forgotPassword']);
Route::get('/reset-password/{reset_token}', [MainController::class, 'resetPassword']);
Route::post('/change-password', [MainController::class, 'changePassword']);
Route::post('/register', [UserController::class, 'register']);

Route::get('/entries', [EntryController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/entries/store', [EntryController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/entries/update/{id}', [EntryController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/entries/destroy', [EntryController::class, 'destroy'])->middleware(ValidateOfficeHours::class);
Route::post('/entries/upload', [EntryController::class, 'upload'])->middleware(ValidateOfficeHours::class);
Route::post('/entries/import', [EntryController::class, 'import'])->middleware(ValidateOfficeHours::class);
Route::get('/entries/view/{id}', [EntryController::class, 'viewDetails'])->middleware(ValidateOfficeHours::class);
Route::get('/entries/edit/{id}', [EntryController::class, 'editDetails'])->middleware(ValidateOfficeHours::class);
Route::get('/entries/getIncentivesMatrix/{id}/{program_id?}', [EntryController::class, 'getIncentivesMatrix'])->middleware(ValidateOfficeHours::class);
Route::get('/entries/getMemberPrograms/{member}', [EntryController::class, 'getMemberPrograms']);

Route::get('/new-sales', [NewSalesController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/new-sales/store', [NewSalesController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/new-sales/update/{id}', [NewSalesController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/new-sales/destroy', [NewSalesController::class, 'destroy'])->middleware(ValidateOfficeHours::class);
Route::post('/new-sales/upload', [NewSalesController::class, 'upload'])->middleware(ValidateOfficeHours::class);
Route::post('/new-sales/import', [NewSalesController::class, 'import'])->middleware(ValidateOfficeHours::class);
Route::get('/new-sales/view/{id}', [NewSalesController::class, 'viewDetails'])->middleware(ValidateOfficeHours::class);
Route::get('/new-sales/edit/{id}', [NewSalesController::class, 'editDetails'])->middleware(ValidateOfficeHours::class);
Route::get('/new-sales/print/{id}', [NewSalesController::class, 'print'])->middleware(ValidateOfficeHours::class);
Route::post('/new-sales/check-member-programs', [NewSalesController::class, 'checkMemberPrograms']);

Route::get('/members', [MemberController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/members/store', [MemberController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/members/update/{id}', [MemberController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/members/destroy', [MemberController::class, 'destroy'])->middleware(ValidateOfficeHours::class);
Route::post('/members/upload', [MemberController::class, 'upload'])->middleware(ValidateOfficeHours::class);
Route::post('/members/loadSheets', [MemberController::class, 'loadSheets'])->middleware(ValidateOfficeHours::class);
Route::get('/members/view/{id}', [MemberController::class, 'viewDetails'])->middleware(ValidateOfficeHours::class);
Route::get('/members/edit/{id}', [MemberController::class, 'editDetails'])->middleware(ValidateOfficeHours::class);
Route::get('/members/print/{id}', [MemberController::class, 'print'])->middleware(ValidateOfficeHours::class);
Route::get('/members/check-or-number', [MemberController::class, 'checkOrNumber'])
    ->name('members.checkOrNumber');
Route::post('/members/validateProgram', [MemberController::class, 'validateProgram'])
    ->name('members.validateProgram');
Route::post('/members/check-name', [MemberController::class, 'checkName'])
    ->name('members.checkName');
Route::post('/members/check-email', [MemberController::class, 'checkEmail'])
    ->name('members.checkEmail');
Route::post('/members/check-app-no', [MemberController::class, 'checkAppNo'])
    ->name('members.checkAppNo');

// Cashflow — main page
Route::get('/expenses', [CashflowController::class, 'index'])->middleware(ValidateOfficeHours::class);
 
// Cashflow — Remittances CRUD
Route::post('/expenses/remittance/store',       [CashflowController::class, 'storeRemittance'])->middleware(ValidateOfficeHours::class);
Route::put('/expenses/remittance/update/{id}',  [CashflowController::class, 'updateRemittance'])->middleware(ValidateOfficeHours::class);
Route::post('/expenses/remittance/destroy',     [CashflowController::class, 'destroyRemittance'])->middleware(ValidateOfficeHours::class);
 
// Cashflow — Expenses CRUD
Route::post('/expenses/expense/store',          [CashflowController::class, 'storeExpense'])->middleware(ValidateOfficeHours::class);
Route::put('/expenses/expense/update/{id}',     [CashflowController::class, 'updateExpense'])->middleware(ValidateOfficeHours::class);
Route::post('/expenses/expense/destroy',        [CashflowController::class, 'destroyExpense'])->middleware(ValidateOfficeHours::class);

// Cashflow — Attachments
Route::post('/expenses/attachment/store',    [CashflowController::class, 'storeAttachment'])->middleware(ValidateOfficeHours::class);
Route::post('/expenses/attachment/destroy',  [CashflowController::class, 'destroyAttachment'])->middleware(ValidateOfficeHours::class);
Route::get('/expenses/attachment/{id}',      [CashflowController::class, 'downloadAttachment'])->middleware(ValidateOfficeHours::class);

Route::get('/audit', [AuditController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/audit/store', [AuditController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/audit/update/{id}', [AuditController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/audit/destroy', [AuditController::class, 'destroy'])->middleware(ValidateOfficeHours::class);

Route::get('/fidelity', [FidelityController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/fidelity/register', [FidelityController::class, 'register'])->middleware(ValidateOfficeHours::class);
Route::post('/fidelity/store', [FidelityController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/fidelity/update/{id}', [FidelityController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/fidelity/destroy', [FidelityController::class, 'destroy'])->middleware(ValidateOfficeHours::class);

Route::get('/reports', [ReportController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/reports/store', [ReportController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/reports/update/{id}', [ReportController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/reports/destroy', [ReportController::class, 'destroy'])->middleware(ValidateOfficeHours::class);
Route::post('/reports/generate', [ReportController::class, 'generate'])->middleware(ValidateOfficeHours::class);

Route::get('/attendance', [AttendanceController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/attendance/store', [AttendanceController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/attendance/update/{id}', [AttendanceController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/attendance/destroy', [AttendanceController::class, 'destroy'])->middleware(ValidateOfficeHours::class);
Route::put('/attendance/admin-update/{id}', [AttendanceController::class, 'adminUpdate'])->middleware(ValidateOfficeHours::class);

Route::get('/branch', [BranchController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/branch/store', [BranchController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/branch/update/{id}', [BranchController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/branch/destroy', [BranchController::class, 'destroy'])->middleware(ValidateOfficeHours::class);

Route::get('/program', [ProgramController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/program/store', [ProgramController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/program/update/{id}', [ProgramController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/program/destroy', [ProgramController::class, 'destroy'])->middleware(ValidateOfficeHours::class);

Route::get('/user-accounts', [UserController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/user-accounts/store', [UserController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/user-accounts/update/{id}', [UserController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/user-accounts/destroy', [UserController::class, 'destroy'])->middleware(ValidateOfficeHours::class);
Route::post('/user-accounts/change_pic', [UserController::class, 'change'])->middleware(ValidateOfficeHours::class);
Route::post('/user-accounts/check-email', [UserController::class, 'checkEmail'])->name('users.checkEmail');
Route::post('/user-accounts/check-contact-num', [UserController::class, 'checkContactNum'])->name('users.checkContactNum');
Route::get('/user-accounts/approve/{id}', [UserController::class, 'approve'])->name('users.approve');

Route::get('/matrix', [MatrixController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::post('/matrix/store', [MatrixController::class, 'store'])->middleware(ValidateOfficeHours::class);
Route::put('/matrix/update/{id}', [MatrixController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/matrix/destroy', [MatrixController::class, 'destroy'])->middleware(ValidateOfficeHours::class);

Route::get('/excel-collection', [ExcelCollectionController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::get('/excel-collection/retrieve', [ExcelCollectionController::class, 'retrieve'])->middleware(ValidateOfficeHours::class);
Route::post('/excel-collection/upload', [ExcelCollectionController::class, 'upload'])->middleware(ValidateOfficeHours::class);
Route::post('/excel-collection/loadSheets', [ExcelCollectionController::class, 'loadSheets'])->middleware(ValidateOfficeHours::class);
Route::get('/excel-collection/view/{id}', [ExcelCollectionController::class, 'viewDetails'])->middleware(ValidateOfficeHours::class);
Route::get('/excel-collection/edit/{id}', [ExcelCollectionController::class, 'editDetails'])->middleware(ValidateOfficeHours::class);
Route::put('/excel-collection/update/{id}', [ExcelCollectionController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/excel-collection/destroy', [ExcelCollectionController::class, 'destroy'])->middleware(ValidateOfficeHours::class);

Route::get('/excel-new-sales', [ExcelNewSalesController::class, 'index'])->middleware(ValidateOfficeHours::class);
Route::get('/excel-new-sales/retrieve', [ExcelNewSalesController::class, 'retrieve'])->middleware(ValidateOfficeHours::class);
Route::post('/excel-new-sales/upload', [ExcelNewSalesController::class, 'upload'])->middleware(ValidateOfficeHours::class);
Route::post('/excel-new-sales/loadSheets', [ExcelNewSalesController::class, 'loadSheets'])->middleware(ValidateOfficeHours::class);
Route::get('/excel-new-sales/view/{id}', [ExcelNewSalesController::class, 'viewDetails'])->middleware(ValidateOfficeHours::class);
Route::get('/excel-new-sales/edit/{id}', [ExcelNewSalesController::class, 'editDetails'])->middleware(ValidateOfficeHours::class);
Route::put('/excel-new-sales/update/{id}', [ExcelNewSalesController::class, 'update'])->middleware(ValidateOfficeHours::class);
Route::post('/excel-new-sales/destroy', [ExcelNewSalesController::class, 'destroy'])->middleware(ValidateOfficeHours::class);
