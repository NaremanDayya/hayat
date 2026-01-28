<?php

namespace App\Livewire;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\HealthCondition;
use Livewire\Component;

class FamilyManager extends Component
{
    public $familyId;
    public $husband_name, $husband_id_number, $husband_dob, $husband_phone, $marital_status = 'متزوج';
    public $wife_name, $wife_id_number, $wife_dob, $wife_phone;
    public $members_count = 0;
    public $original_address, $current_address;

    public function mount($id = null)
    {
//        test
        if ($id) {
            $this->familyId = $id;
            $family = Family::with(['members', 'healthConditions'])->findOrFail($id);

            $this->husband_name = $family->husband_name;
            $this->husband_id_number = $family->husband_id_number;
            $this->husband_dob = $family->husband_dob;
            $this->husband_phone = $family->husband_phone;
            $this->marital_status = $family->marital_status;

            $this->wife_name = $family->wife_name;
            $this->wife_id_number = $family->wife_id_number;
            $this->wife_dob = $family->wife_dob;
            $this->wife_phone = $family->wife_phone;

            $this->original_address = $family->original_address;
            $this->current_address = $family->current_address;
            $this->members_count = $family->family_members_count;

            $this->sons = $family->members->where('gender', 'male')->map(function($m) {
                return ['name' => $m->name, 'id_number' => $m->id_number, 'dob' => $m->dob];
            })->toArray();

            $this->daughters = $family->members->where('gender', 'female')->map(function($m) {
                return ['name' => $m->name, 'id_number' => $m->id_number, 'dob' => $m->dob];
            })->toArray();

            $this->health_conditions = $family->healthConditions->map(function($h) {
                return ['person_name' => $h->person_name, 'details' => $h->condition_details];
            })->toArray();
        }
    }

    public $sons = [];
    public $daughters = [];
    public $health_conditions = [];

    protected $rules = [
        'husband_name' => 'required|string',
        'husband_id_number' => 'nullable',
        'wife_name' => 'nullable|string',
        'current_address' => 'required',
    ];

    public function addSon()
    {
        $this->sons[] = ['name' => '', 'id_number' => '', 'dob' => ''];
        $this->updateCount();
    }

    public function removeSon($index)
    {
        unset($this->sons[$index]);
        $this->sons = array_values($this->sons);
        $this->updateCount();
    }

    public function addDaughter()
    {
        $this->daughters[] = ['name' => '', 'id_number' => '', 'dob' => ''];
        $this->updateCount();
    }

    public function removeDaughter($index)
    {
        unset($this->daughters[$index]);
        $this->daughters = array_values($this->daughters);
        $this->updateCount();
    }

    public function addHealthCondition()
    {
        $this->health_conditions[] = ['person_name' => '', 'details' => ''];
    }

    public function removeHealthCondition($index)
    {
        unset($this->health_conditions[$index]);
        $this->health_conditions = array_values($this->health_conditions);
    }

    public function updateCount()
    {
        // 2 parents + count of children
        $this->members_count = 2 + count($this->sons) + count($this->daughters);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'husband_name' => $this->husband_name,
            'husband_id_number' => $this->husband_id_number,
            'husband_dob' => $this->husband_dob,
            'husband_phone' => $this->husband_phone,
            'marital_status' => $this->marital_status,
            'wife_name' => $this->wife_name,
            'wife_id_number' => $this->wife_id_number,
            'wife_dob' => $this->wife_dob,
            'wife_phone' => $this->wife_phone,
            'family_members_count' => $this->members_count,
            'original_address' => $this->original_address,
            'current_address' => $this->current_address,
        ];

        if ($this->familyId) {
            $family = Family::findOrFail($this->familyId);
            $family->update($data);
            $family->members()->delete();
            $family->healthConditions()->delete();
        } else {
            $family = Family::create($data);
        }

        foreach ($this->sons as $son) {
            FamilyMember::create([
                'family_id' => $family->id,
                'name' => $son['name'],
                'id_number' => $son['id_number'],
                'dob' => $son['dob'],
                'gender' => 'male',
            ]);
        }

        foreach ($this->daughters as $daughter) {
            FamilyMember::create([
                'family_id' => $family->id,
                'name' => $daughter['name'],
                'id_number' => $daughter['id_number'],
                'dob' => $daughter['dob'],
                'gender' => 'female',
            ]);
        }

        foreach ($this->health_conditions as $condition) {
            HealthCondition::create([
                'family_id' => $family->id,
                'person_name' => $condition['person_name'],
                'condition_details' => $condition['details'],
            ]);
        }

        $this->dispatch('swal', [
            'title' => 'تم الحفظ!',
            'text' => 'تمت إضافة العائلة بنجاح.',
            'icon' => 'success'
        ]);

        return redirect()->route('dashboard.families');
    }

    public function render()
    {
        return view('livewire.family-manager');
    }
}
