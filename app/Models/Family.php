<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = [
        'husband_name', 'husband_id_number', 'husband_dob', 'husband_phone', 'marital_status',
        'wife_name', 'wife_id_number', 'wife_dob', 'wife_phone',
        'family_members_count', 'original_address', 'current_address'
    ];

    public function members()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function healthConditions()
    {
        return $this->hasMany(HealthCondition::class);
    }

    public function getCalculatedMembersCountAttribute()
    {
        return $this->members->count();
    }
}
