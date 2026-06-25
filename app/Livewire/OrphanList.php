<?php

namespace App\Livewire;

use App\Models\FamilyMember;
use Livewire\Component;
use Livewire\WithPagination;

class OrphanList extends Component
{
    use WithPagination;

    public $search = '';
    public $minAge = null;
    public $maxAge = null;

    protected $queryString = ['search', 'minAge', 'maxAge'];

    public function resetFilters()
    {
        $this->reset(['search', 'minAge', 'maxAge']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = FamilyMember::with('family')
            ->where(function ($q) {
                $q->whereHas('family', function ($q2) {
                    $q2->where(function ($q3) {
                        $q3->whereNull('husband_name')->orWhere('husband_name', '');
                    });
                })->orWhereHas('family', function ($q2) {
                    $q2->where(function ($q3) {
                        $q3->whereNull('wife_name')->orWhere('wife_name', '');
                    });
                });
            });

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('id_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->minAge || $this->maxAge) {
            $dateEnd = $this->minAge ? now()->subYears($this->minAge)->endOfDay()->format('Y-m-d') : now()->format('Y-m-d');
            $dateStart = $this->maxAge ? now()->subYears($this->maxAge)->startOfDay()->format('Y-m-d') : '1900-01-01';
            $query->whereBetween('dob', [$dateStart, $dateEnd]);
        }

        return view('livewire.orphan-list', [
            'orphans' => $query->latest()->paginate(10)
        ]);
    }
}
