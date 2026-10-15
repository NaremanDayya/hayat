<?php

namespace App\Livewire;

use App\Models\Family;
use Livewire\Component;

class FamilyDetails extends Component
{
    public $family;

    public function mount($id)
    {
        $this->family = Family::with(['members', 'healthConditions'])->findOrFail($id);
    }

    public function exportExcel()
    {
        $family = Family::with(['members', 'healthConditions'])->findOrFail($this->family->id);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\FamilyDetailsExport($family),
            'family_' . $family->id . '.xlsx'
        );
    }

    public function deleteFamily()
    {
        $this->family->delete();
        $this->dispatch('swal', [
            'title' => 'تم الحذف!',
            'text' => 'تم حذف العائلة بنجاح.',
            'icon' => 'success'
        ]);
        return redirect()->route('dashboard.families');
    }

    public function render()
    {
        return view('livewire.family-details');
    }
}
