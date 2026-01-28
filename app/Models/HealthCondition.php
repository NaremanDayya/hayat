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
}
