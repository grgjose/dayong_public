<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remittance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'remittances';
 
    protected $fillable = [
        'branch_id',
        'encoder_id',
        'mas_id',
        'mas_name',
        'transaction_type',
        'amount',
        'bank_name',
        'gcash_number',
        'reference_number',
        'transaction_date',
        'remarks',
    ];

    /**
     * All file attachments for this remittance record.
     */
    public function attachments()
    {
        return $this->morphMany(CashflowAttachment::class, 'attachable');
    }
}
