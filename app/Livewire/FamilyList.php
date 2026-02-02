<?php

namespace App\Livewire;

use App\Models\Family;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class FamilyList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $file;
    public $minAge = null;
    public $maxAge = null;
    public $hasDisease = false;
    public $sortBy = 'latest'; // 'latest', 'name_asc', 'name_desc'
    
    protected $queryString = ['search', 'minAge', 'maxAge', 'hasDisease', 'sortBy'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'minAge' => $this->minAge,
            'maxAge' => $this->maxAge,
            'hasDisease' => $this->hasDisease,
        ];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\FamiliesExport($filters), 'families_export.xlsx');
    }

    public function downloadSample()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\FamilySampleExport, 'families_sample.xlsx');
    }

    public function importExcel()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\FamiliesImport, $this->file->getRealPath());
            
            $this->dispatch('swal', [
                'title' => 'تم الاستيراد!',
                'text' => 'تمت إضافة البيانات من الملف بنجاح.',
                'icon' => 'success'
            ]);
            
            $this->file = null;
        } catch (\Exception $e) {
            \Log::error('Import Error: ' . $e->getMessage());
            $this->dispatch('swal', [
                'title' => 'خطأ!',
                'text' => 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    // Age filter helper removed as we use direct inputs


    public function resetFilters()
    {
        $this->reset(['search', 'minAge', 'maxAge', 'hasDisease', 'sortBy']);
    }

    public function sortByName()
    {
        if ($this->sortBy === 'name_asc') {
            $this->sortBy = 'name_desc';
        } elseif ($this->sortBy === 'name_desc') {
            $this->sortBy = 'latest';
        } else {
            $this->sortBy = 'name_asc';
        }
        $this->resetPage();
    }

    public function deleteFamily($id)
    {
        $family = Family::find($id);
        if ($family) {
            $family->delete();
            $this->dispatch('swal', [
                'title' => 'تم الحذف!',
                'text' => 'تم حذف العائلة والبيانات المرتبطة بها بنجاح.',
                'icon' => 'success'
            ]);
        }
    }

    public function render()
    {
        $query = Family::query()
            ->with(['members', 'healthConditions']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('husband_name', 'like', '%' . $this->search . '%')
                  ->orWhere('wife_name', 'like', '%' . $this->search . '%')
                  ->orWhere('husband_id_number', 'like', '%' . $this->search . '%')
                  ->orWhere('wife_id_number', 'like', '%' . $this->search . '%')
                  ->orWhere('original_address', 'like', '%' . $this->search . '%')
                  ->orWhere('current_address', 'like', '%' . $this->search . '%')
                  ->orWhere('husband_phone', 'like', '%' . $this->search . '%')
                  ->orWhere('wife_phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->hasDisease) {
            $query->has('healthConditions');
        }

        // Family age filter usually applies to members or parents? 
        // User said "age filter as pop up... he will insert the age... older or younger".
        // In the main family table, maybe we filter by any member's age or parent's age?
        // Let's assume parent's age for now in main table, and child's age in child tables.
        if ($this->minAge || $this->maxAge) {
            $query->where(function($q) {
                // Calculate dates
                // minAge means "At least X years old" => Born BEFORE (Today - minAge)
                // maxAge means "At most Y years old" => Born AFTER (Today - maxAge)
                
                $dateMax = $this->minAge ? now()->subYears($this->minAge)->format('Y-m-d') : now()->subYears(100)->format('Y-m-d'); // Default old enough
                $dateMin = $this->maxAge ? now()->subYears($this->maxAge)->format('Y-m-d') : now()->subYears(0)->format('Y-m-d');   // Default young enough

                // Logic: (Husband DOB between min/max) OR (Wife DOB between min/max)
                // Note: Dates are reversed. Younger people have larger DOB value (later date).
                // So "Between Age 20 and 30" => DOB between (Today-30) and (Today-20).
                // $dateMin is the OLDER date (from maxAge). $dateMax is the NEWER date (from minAge).
                // Wait.
                // Age 30 (maxAge) => Born 1994. Age 20 (minAge) => Born 2004.
                // Range: 1994 to 2004.
                // So start date is (Today - MaxAge), end date is (Today - MinAge).
                // Eloquent whereBetween expects [start, end] (usually chronological).
                
                $startDate = $this->maxAge ? now()->subYears($this->maxAge)->startOfDay()->format('Y-m-d') : '1900-01-01';
                $endDate = $this->minAge ? now()->subYears($this->minAge)->endOfDay()->format('Y-m-d') : now()->format('Y-m-d');

                $q->whereBetween('husband_dob', [$startDate, $endDate])
                  ->orWhereBetween('wife_dob', [$startDate, $endDate]);
            });
        }

        // Apply sorting
        if ($this->sortBy === 'name_asc') {
            $query->orderBy('husband_name', 'asc');
        } elseif ($this->sortBy === 'name_desc') {
            $query->orderBy('husband_name', 'desc');
        } else {
            $query->latest();
        }

        return view('livewire.family-list', [
            'families' => $query->paginate(10)
        ]);
    }
}
