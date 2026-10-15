<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FamilyMember extends Model
{
    public const WIDOW_STATUSES = ['أرملة', 'ارملة'];
    public const DIVORCED_STATUSES = ['مطلقة'];

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

    /**
     * All females in the system: daughters, wives, and women who head a family
     * alone (widowed/divorced, stored in the family's husband_* columns).
     */
    public static function femalesQuery(array $filters = [])
    {
        $daughters = DB::table('family_members')
            ->join('families', 'families.id', '=', 'family_members.family_id')
            ->where('family_members.gender', 'female')
            ->select([
                'family_members.id',
                'family_members.name',
                'family_members.id_number',
                'family_members.dob',
                'family_members.family_id',
                'families.husband_name',
                'families.husband_id_number',
                'families.husband_phone',
                'families.original_address',
                'family_members.created_at',
                DB::raw("'member' as type"),
                DB::raw('NULL as marital_status'),
            ]);

        $wives = DB::table('families')
            ->whereNotNull('wife_name')
            ->where('wife_name', '!=', '')
            ->select([
                'families.id',
                'families.wife_name as name',
                'families.wife_id_number as id_number',
                'families.wife_dob as dob',
                'families.id as family_id',
                DB::raw('NULL as husband_name'),
                DB::raw('NULL as husband_id_number'),
                'families.husband_phone',
                'families.original_address',
                'families.created_at',
                DB::raw("'parent' as type"),
                DB::raw('NULL as marital_status'),
            ]);

        $heads = DB::table('families')
            ->where(fn ($q) => $q->whereNull('wife_name')->orWhere('wife_name', ''))
            ->whereNotNull('husband_name')
            ->where('husband_name', '!=', '')
            ->whereIn('marital_status', array_merge(self::WIDOW_STATUSES, self::DIVORCED_STATUSES))
            ->select([
                'families.id',
                'families.husband_name as name',
                'families.husband_id_number as id_number',
                'families.husband_dob as dob',
                'families.id as family_id',
                DB::raw('NULL as husband_name'),
                DB::raw('NULL as husband_id_number'),
                'families.husband_phone',
                'families.original_address',
                'families.created_at',
                DB::raw("'head' as type"),
                'families.marital_status',
            ]);

        $query = DB::query()->fromSub($daughters->unionAll($wives)->unionAll($heads), 't');

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('id_number', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['minAge']) || !empty($filters['maxAge'])) {
            $dateEnd = !empty($filters['minAge']) ? now()->subYears($filters['minAge'])->endOfDay()->format('Y-m-d') : now()->format('Y-m-d');
            $dateStart = !empty($filters['maxAge']) ? now()->subYears($filters['maxAge'])->startOfDay()->format('Y-m-d') : '1900-01-01';
            $query->whereBetween('dob', [$dateStart, $dateEnd]);
        }

        if (!empty($filters['personType'])) {
            $query->where('type', $filters['personType']);
        }

        $statuses = match ($filters['maritalFilter'] ?? '') {
            'widow' => self::WIDOW_STATUSES,
            'divorced' => self::DIVORCED_STATUSES,
            'widow_divorced' => array_merge(self::WIDOW_STATUSES, self::DIVORCED_STATUSES),
            default => null,
        };
        if ($statuses) {
            $query->whereIn('marital_status', $statuses);
        }

        $health = $filters['hasHealthCondition'] ?? '';
        if ($health === 'yes' || $health === 'no') {
            $method = $health === 'yes' ? 'whereExists' : 'whereNotExists';
            $query->{$method}(function ($q) {
                $q->select(DB::raw(1))
                  ->from('health_conditions')
                  ->whereColumn('health_conditions.family_id', 't.family_id')
                  ->whereColumn('health_conditions.person_name', 't.name');
            });
        }

        return $query;
    }
}
