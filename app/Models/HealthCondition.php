<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCondition extends Model
{
    protected $fillable = ['family_id', 'person_name', 'condition_details'];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function getPersonGenderAttribute()
    {
        // Check if person matches husband
        if ($this->family && $this->person_name === $this->family->husband_name) {
            return 'male';
        }

        // Check if person matches wife
        if ($this->family && $this->person_name === $this->family->wife_name) {
            return 'female';
        }

        // Check if person matches a child
        if ($this->family && $this->family->members) {
            $member = $this->family->members->where('name', $this->person_name)->first();
            return $member ? $member->gender : 'N/A';
        }
        return 'N/A';
    }

    public function getPersonIdNumberAttribute()
    {
        // Check if person matches husband
        if ($this->family && $this->person_name === $this->family->husband_name) {
            return $this->family->husband_id_number;
        }

        // Check if person matches wife
        if ($this->family && $this->person_name === $this->family->wife_name) {
            return $this->family->wife_id_number;
        }

        // Check if person matches a child
        if ($this->family && $this->family->members) {
            $member = $this->family->members->where('name', $this->person_name)->first();
            return $member ? $member->id_number : null;
        }
        return null;
    }

    public function getPersonDobAttribute()
    {
        if ($this->family && $this->person_name === $this->family->husband_name) {
            return $this->family->husband_dob;
        }

        if ($this->family && $this->person_name === $this->family->wife_name) {
            return $this->family->wife_dob;
        }

        if ($this->family && $this->family->members) {
            $member = $this->family->members->where('name', $this->person_name)->first();
            return $member ? $member->dob : null;
        }
        return null;
    }

    public function getPersonAgeAttribute()
    {
        $dob = $this->person_dob;
        if (!$dob) return null;
        return \Carbon\Carbon::parse($dob)->age;
    }

    public function getPersonPhoneAttribute()
    {
        if ($this->family && $this->person_name === $this->family->husband_name) {
            return $this->family->husband_phone;
        }

        if ($this->family && $this->person_name === $this->family->wife_name) {
            return $this->family->wife_phone;
        }

        return null;
    }

    public function getPersonOriginalAddressAttribute()
    {
        return $this->family ? $this->family->original_address : null;
    }

    public function getPersonCurrentAddressAttribute()
    {
        return $this->family ? $this->family->current_address : null;
    }

    public function scopeFilterByGender($query, $gender)
    {
        if (!$gender) {
            return $query;
        }

        return $query->where(function ($q) use ($gender) {
            if ($gender === 'male') {
                $q->whereHas('family', function ($q) {
                    $q->whereColumn('husband_name', 'health_conditions.person_name');
                })->orWhereHas('family.members', function ($q) {
                    $q->where('gender', 'male')
                      ->whereColumn('name', 'health_conditions.person_name');
                });
            } elseif ($gender === 'female') {
                $q->whereHas('family', function ($q) {
                    $q->whereColumn('wife_name', 'health_conditions.person_name');
                })->orWhereHas('family.members', function ($q) {
                    $q->where('gender', 'female')
                      ->whereColumn('name', 'health_conditions.person_name');
                });
            }
        });
    }
}
