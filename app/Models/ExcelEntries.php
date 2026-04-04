<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelEntries extends Model
{
    use HasFactory;

    protected $table = 'excel_entries';

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
        'or_number',
        'or_date',
        'amount_collected',
        'month_of',
        'nop',
        'date_remitted',
        'dayong_program',
        'reactivation',
        'transferred',
    ];

}
