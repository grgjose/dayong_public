<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelMembers extends Model
{
    use HasFactory;

    protected $table = 'excel_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'timestamp',
        'branch',
        'marketting_agent',
        'status',
        'phmember',
        'address',
        'civil_status',
        'birthdate',
        'age',
        'name',
        'contact_num',
        'type_of_transaction',
        'with_registration_fee',
        'registration_amount',
        'dayong_program',
        'application_no',
        'or_number',
        'or_date',
        'amount_collected',
        'name1', 'age1', 'relationship1',
        'name2', 'age2', 'relationship2',
        'name3', 'age3', 'relationship3',
        'name4', 'age4', 'relationship4',
        'name5', 'age5', 'relationship5',
        'sheetName',
        'remarks',
        'isImported',
    ];

}
