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
    public $minAge = null;
    public $maxAge = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'gender' => ['except' => ''],
        'minAge' => ['except' => null],
        'maxAge' => ['except' => null],
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

    public function updatingMinAge()
    {
        $this->resetPage();
    }

    public function updatingMaxAge()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'gender', 'minAge', 'maxAge']);
    }

    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'gender' => $this->gender,
        ];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\HealthConditionsExport($filters), 'health_conditions_export.xlsx');
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

        if ($this->minAge || $this->maxAge) {
            $query->filterByAge($this->minAge, $this->maxAge);
        }

        $healthConditions = $query->with('family.members')->paginate(10);

        return view('livewire.health-condition-list', [
            'healthConditions' => $healthConditions
        ]);
    }
}
