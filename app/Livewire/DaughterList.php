<?php

namespace App\Livewire;

use App\Models\FamilyMember;
use Livewire\Component;
use Livewire\WithPagination;

class DaughterList extends Component
{
    use WithPagination, \Livewire\WithFileUploads;

    public $search = '';
    public $minAge = null;
    public $maxAge = null;
    public $file;
    
    protected $queryString = ['search', 'minAge', 'maxAge'];

    public function resetFilters()
    {
        $this->reset(['search', 'minAge', 'maxAge']);
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

        if ($this->minAge || $this->maxAge) {
             $dateEnd = $this->minAge ? now()->subYears($this->minAge)->endOfDay()->format('Y-m-d') : now()->format('Y-m-d');
             $dateStart = $this->maxAge ? now()->subYears($this->maxAge)->startOfDay()->format('Y-m-d') : '1900-01-01';
             $query->whereBetween('dob', [$dateStart, $dateEnd]);
        }

        return view('livewire.daughter-list', [
            'daughters' => $query->latest()->paginate(10)
        ]);
    }
}
