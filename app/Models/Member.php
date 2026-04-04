<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $fillable = [
        'fname',
        'mname',
        'lname',
        'ext',
        'contact_num',
        'email',
        'birthdate',
        'sex',
        'birthplace',
        'citizenship',
        'civil_status',
        'address',
        'agent_id',
        'encoder_id',
        'claimant_id',
    ];

    public function beneficiaries()
    {
        return $this->belongsToMany(Beneficiary::class, 'beneficiary_members')
            ->withPivot('relationship')
            ->withTimestamps();
    }

    public function claimant()
    {
        return $this->belongsTo(Claimant::class, 'claimant_id');
    }

}
