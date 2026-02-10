<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HealthCondition;

class HealthConditionList extends Component
{
    use WithPagination;

    public $search = '';
    public $gender = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'gender' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGender()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = HealthCondition::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('person_name', 'like', '%' . $this->search . '%')
                  ->orWhere('condition_details', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->gender) {
            $query->filterByGender($this->gender);
        }

        $healthConditions = $query->with('family.members')->paginate(10);

        return view('livewire.health-condition-list', [
            'healthConditions' => $healthConditions
        ]);
    }
}
