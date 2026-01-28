<?php

namespace App\Livewire;

use App\Models\FamilyMember;
use Livewire\Component;
use Livewire\WithPagination;

class DaughterList extends Component
{
    use WithPagination, \Livewire\WithFileUploads;

    public $search = '';
    public $filterAge = null;
    public $ageCondition = '>=';
    public $file;
    
    protected $queryString = ['search', 'filterAge', 'ageCondition'];

    public function resetFilters()
    {
        $this->reset(['search', 'filterAge', 'ageCondition']);
    }

    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DaughtersExport, 'daughters_export.xlsx');
    }

    public function downloadSample()
    {
        $filePath = 'samples/daughters_sample.xlsx';
        
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'الاسم ثلاثي');
            $sheet->setCellValue('B1', 'رقم الهوية');
            $sheet->setCellValue('C1', 'تاريخ الميلاد');
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('samples')) {
                \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('samples');
            }
            
            $writer->save(storage_path('app/public/' . $filePath));
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($filePath);
    }

    public function importExcel()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\FamilyMembersImport('female'), $this->file);
            $this->reset('file');
            session()->flash('message', 'تم استيراد البيانات بنجاح');
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteDaughter($id)
    {
        $daughter = FamilyMember::find($id);
        if ($daughter) {
            $daughter->delete();
            $this->dispatch('swal', [
                'title' => 'تم الحذف!',
                'text' => 'تم حذف البنت بنجاح.',
                'icon' => 'success'
            ]);
        }
    }

    public function render()
    {
        $query = FamilyMember::where('gender', 'female')->with('family');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('id_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterAge !== null && $this->filterAge !== '') {
            if (str_contains($this->filterAge, '-')) {
                $range = explode('-', $this->filterAge);
                if (count($range) === 2) {
                    $minAge = trim($range[0]);
                    $maxAge = trim($range[1]);
                    
                    if (is_numeric($minAge) && is_numeric($maxAge)) {
                        $dateEnd = now()->subYears($minAge)->endOfDay()->format('Y-m-d');
                        $dateStart = now()->subYears($maxAge)->startOfDay()->format('Y-m-d');
                        $query->whereBetween('dob', [$dateStart, $dateEnd]);
                    }
                }
            } else if (is_numeric($this->filterAge)) {
                $targetDate = now()->subYears($this->filterAge)->format('Y-m-d');
                if ($this->ageCondition === '>=') {
                    $query->where('dob', '<=', $targetDate);
                } elseif ($this->ageCondition === '<=') {
                    $query->where('dob', '>=', $targetDate);
                } else {
                    $query->whereYear('dob', now()->subYears($this->filterAge)->year);
                }
            }
        }

        return view('livewire.daughter-list', [
            'daughters' => $query->latest()->paginate(10)
        ]);
    }
}
