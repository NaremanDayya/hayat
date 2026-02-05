<div class="card">
    <div class="flex-between" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>بيانات الأبناء الذكور</h3>
        @if (session()->has('message'))
            <div style="background: #27ae60; color: white; padding: 10px; border-radius: 5px; margin-bottom: 10px;">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div style="background: #e74c3c; color: white; padding: 10px; border-radius: 5px; margin-bottom: 10px;">{{ session('error') }}</div>
        @endif
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث في الاسم أو الهوية..." style="width: 300px;">
            
            <button wire:click="resetFilters" class="btn" style="background: #95a5a6; color: white;">مسح الفلاتر</button>
            <button wire:click="exportExcel" class="btn" style="background: #27ae60; color: white;">تصدير Excel</button>
            <button wire:click="downloadSample" class="btn" style="background: #3498db; color: white;">تحميل نموذج الرفع</button>
            
            <div style="display: flex; gap: 5px; align-items: center; background: rgba(0,0,0,0.05); padding: 5px 10px; border-radius: 8px;">
                <input type="file" wire:model="file" style="padding: 0; width: 200px; border: none; background: transparent;">
                <button wire:click="importExcel" class="btn" style="background: #8e44ad; color: white; padding: 5px 15px;" wire:loading.attr="disabled">
                    <span wire:loading.remove>رفع الملف</span>
                    <span wire:loading>جاري الرفع...</span>
                </button>
            </div>

            <div style="position: relative;" x-data="{ open: false }">
                <button @click="open = !open" class="btn btn-primary">فلترة العمر</button>
                <div x-show="open" @click.away="open = false" class="card" style="position: absolute; top: 100%; left: 0; z-index: 100; width: 250px; background: white; margin-top: 10px;">
                    <label>العمر من:</label>
                    <input type="number" wire:model.live="minAge" placeholder="أقل عمر">
                    
                    <label style="margin-top:10px; display:block;">إلى:</label>
                    <input type="number" wire:model.live="maxAge" placeholder="أكبر عمر">
                    
                    <button @click="open = false" class="btn btn-primary" style="margin-top: 10px; width: 100%;">تطبيق</button>
                    <button wire:click="resetFilters" @click="open = false" class="btn" style="margin-top: 5px; width: 100%; background: #eee;">تفريغ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>رقم الهوية</th>
                    <th>تاريخ الميلاد</th>
                    <th>العمر</th>
                    <th>اسم الأب</th>
                    <th>رقم هاتف الأب</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sons as $son)
                <tr>
                    <td>{{ $son->name }}</td>
                    <td>{{ $son->id_number }}</td>
                    <td>{{ $son->dob ? $son->dob->translatedFormat('j F Y') : '-' }}</td>
                    <td>{{ $son->age }}</td>
                    <td>{{ $son->family->husband_name }}</td>
                    <td>{{ $son->family->husband_phone }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('dashboard.family-details', $son->family_id) }}" class="btn btn-primary">عرض العائلة</a>
                            <button wire:click="deleteSon({{ $son->id }})" wire:confirm="هل أنت متأكد من حذف هذا الابن؟" class="btn" style="background: #e74c3c; color: white; padding: 5px 10px;">حذف</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $sons->links('vendor.livewire.custom-pagination') }}
    </div>
</div>
