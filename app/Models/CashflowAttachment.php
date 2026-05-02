<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashflowAttachment extends Model
{
    use HasFactory;
    use SoftDeletes;
 
    protected $table = 'cashflow_attachments';
 
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'file_path',
        'original_name',
        'uploaded_by',
    ];
 
    /**
     * Polymorphic: belongs to either a Remittance or an Expense.
     */
    public function attachable()
    {
        return $this->morphTo();
    }
}
