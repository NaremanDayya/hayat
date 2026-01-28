<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $fillable = ['family_id', 'name', 'id_number', 'dob', 'gender'];
    protected $casts = ['dob' => 'date'];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function getAgeAttribute()
    {
        if (!$this->dob) return null;
        return \Carbon\Carbon::parse($this->dob)->age;
    }
}
