<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;
    
    protected $table = 'beneficiaries';

    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'ext',
        'birthdate',
        'sex',
        'relationship', // ⚠️ remove this if relationship is only in pivot table
        'contact_num',
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class, 'beneficiary_members')
            ->withPivot('relationship')
            ->withTimestamps();
    }

}
