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
    public $filterAge = null;
    public $ageCondition = '>='; // '>=', '<=', '='
    public $hasDisease = false;
    
    protected $queryString = ['search', 'filterAge', 'ageCondition', 'hasDisease'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\FamiliesExport, 'families_export.xlsx');
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

    public function setAgeFilter($age, $condition)
    {
        $this->filterAge = $age;
        $this->ageCondition = $condition;
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterAge', 'ageCondition', 'hasDisease']);
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
        if ($this->filterAge !== null && $this->filterAge !== '') {
            $query->where(function($q) {
                $targetDate = now()->subYears($this->filterAge)->format('Y-m-d');
                if ($this->ageCondition === '>=') {
                    // Older than X means born BEFORE target date
                    $q->where('husband_dob', '<=', $targetDate)
                      ->orWhere('wife_dob', '<=', $targetDate);
                } elseif ($this->ageCondition === '<=') {
                    // Younger than X means born AFTER target date
                    $q->where('husband_dob', '>=', $targetDate)
                      ->orWhere('wife_dob', '>=', $targetDate);
                } else {
                    $q->whereYear('husband_dob', now()->subYears($this->filterAge)->year)
                      ->orWhereYear('wife_dob', now()->subYears($this->filterAge)->year);
                }
            });
        }

        return view('livewire.family-list', [
            'families' => $query->latest()->paginate(10)
        ]);
    }
}
