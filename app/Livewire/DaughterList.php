<?php

namespace App\Livewire;

use App\Models\FamilyMember;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class DaughterList extends Component
{
    use WithPagination, \Livewire\WithFileUploads;

    public $search = '';
    public $minAge = null;
    public $maxAge = null;
    public $file;
    public $hasHealthCondition = '';
    public $personType = '';

    protected $queryString = ['search', 'minAge', 'maxAge', 'hasHealthCondition', 'personType'];

    public function resetFilters()
    {
        $this->reset(['search', 'minAge', 'maxAge', 'hasHealthCondition', 'personType']);
    }

    public function updatingPersonType()
    {
        $this->resetPage();
    }

    public function exportExcel()
    {
        $filters = [
            'search' => $this->search,
            'minAge' => $this->minAge,
            'maxAge' => $this->maxAge,
            'hasHealthCondition' => $this->hasHealthCondition,
        ];
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\DaughtersExport($filters), 'daughters_export.xlsx');
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
        $members = DB::table('family_members')
            ->join('families', 'families.id', '=', 'family_members.family_id')
            ->where('family_members.gender', 'female')
            ->select([
                'family_members.id',
                'family_members.name',
                'family_members.id_number',
                'family_members.dob',
                'family_members.family_id',
                'families.husband_name',
                'families.husband_phone',
                'families.original_address',
                'family_members.created_at',
                DB::raw("'member' as type"),
            ]);

        $parents = DB::table('families')
            ->whereNotNull('wife_name')
            ->where('wife_name', '!=', '')
            ->select([
                'families.id',
                'families.wife_name as name',
                'families.wife_id_number as id_number',
                'families.wife_dob as dob',
                'families.id as family_id',
                DB::raw('NULL as husband_name'),
                'families.husband_phone',
                'families.original_address',
                'families.created_at',
                DB::raw("'parent' as type"),
            ]);

        $union = $members->unionAll($parents);

        $query = DB::query()->fromSub($union, 't');

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

        if ($this->personType) {
            $query->where('type', $this->personType);
        }

        if ($this->hasHealthCondition !== '') {
            if ($this->hasHealthCondition === 'yes') {
                $query->whereExists(function($q) {
                    $q->select(DB::raw(1))
                      ->from('health_conditions')
                      ->whereColumn('health_conditions.family_id', 't.family_id')
                      ->whereColumn('health_conditions.person_name', 't.name');
                });
            } elseif ($this->hasHealthCondition === 'no') {
                $query->whereNotExists(function($q) {
                    $q->select(DB::raw(1))
                      ->from('health_conditions')
                      ->whereColumn('health_conditions.family_id', 't.family_id')
                      ->whereColumn('health_conditions.person_name', 't.name');
                });
            }
        }

        return view('livewire.daughter-list', [
            'daughters' => $query->orderByDesc('created_at')->paginate(10)
        ]);
    }
}
