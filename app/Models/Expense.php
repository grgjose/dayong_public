<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'expenses';
 
    protected $fillable = [
        'branch_id',
        'encoder_id',
        'mas_id',
        'member_id',
        'type_of_expense',
        'receipt_number',
        'amount',
        'transaction_date',
        'remarks',
    ];

    /**
     * All file attachments for this expense record.
     */
    public function attachments()
    {
        return $this->morphMany(CashflowAttachment::class, 'attachable');
    }

}
