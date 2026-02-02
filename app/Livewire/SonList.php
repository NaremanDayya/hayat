<?php

namespace App\Livewire;

use App\Models\FamilyMember;
use Livewire\Component;
use Livewire\WithPagination;

class SonList extends Component
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
        $filters = [
            'search' => $this->search,
            'minAge' => $this->minAge,
            'maxAge' => $this->maxAge,
        ];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\SonsExport($filters), 'sons_export.xlsx');
    }

    public function downloadSample()
    {
        $filePath = 'samples/sons_sample.xlsx';
        
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
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\FamilyMembersImport('male'), $this->file);
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

    public function deleteSon($id)
    {
        $son = FamilyMember::find($id);
        if ($son) {
            $son->delete();
            $this->dispatch('swal', [
                'title' => 'تم الحذف!',
                'text' => 'تم حذف الابن بنجاح.',
                'icon' => 'success'
            ]);
        }
    }

    public function render()
    {
        $query = FamilyMember::where('gender', 'male')->with('family');

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

        return view('livewire.son-list', [
            'sons' => $query->latest()->paginate(10)
        ]);
    }
}
