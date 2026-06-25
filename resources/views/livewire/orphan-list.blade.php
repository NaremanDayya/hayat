<div class="card">
    <div class="flex-between" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>الأيتام</h3>

        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث في الاسم أو الهوية..." style="width: 300px;">

            <div style="position: relative;" x-data="{ open: false }">
                <button @click="open = !open" class="btn btn-primary" type="button">فلترة العمر</button>
                <div x-show="open" @click.away="open = false" class="card" style="position: absolute; top: 100%; left: 0; z-index: 100; width: 250px; background: white; margin-top: 10px;">
                    <label>العمر من:</label>
                    <input type="number" wire:model.live="minAge" placeholder="أقل عمر">

                    <label style="margin-top:10px; display:block;">إلى:</label>
                    <input type="number" wire:model.live="maxAge" placeholder="أكبر عمر">

                    <button @click="open = false" class="btn btn-primary" style="margin-top: 10px; width: 100%;">تطبيق</button>
                    <button wire:click="resetFilters" @click="open = false" class="btn" style="margin-top: 5px; width: 100%; background: #eee;">تفريغ</button>
                </div>
            </div>

            <button wire:click="resetFilters" class="btn" style="background: #95a5a6; color: white;">مسح الفلاتر</button>
        </div>
    </div>

    <div style="margin-bottom: 15px;">
        <span style="background: #eef4ff; color: #2c3e50; padding: 6px 14px; border-radius: 20px; font-weight: bold; font-size: 0.9em; display: inline-block;">
            عدد النتائج: {{ $orphans->total() }}
        </span>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>رقم الهوية</th>
                    <th>تاريخ الميلاد</th>
                    <th>العمر</th>
                    <th>اسم ولي الأمر</th>
                    <th>السكن الأصلي</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orphans as $orphan)
                <tr>
                    <td>{{ $orphan->name }}</td>
                    <td>{{ $orphan->id_number }}</td>
                    <td>{{ $orphan->dob ? $orphan->dob->translatedFormat('j F Y') : '-' }}</td>
                    <td>{{ $orphan->age }}</td>
                    <td>{{ $orphan->family->husband_name ?? '-' }}</td>
                    <td>{{ $orphan->family->original_address ?? '-' }}</td>
                    <td>
                        <a href="{{ route('dashboard.family-details', $orphan->family_id) }}" class="btn btn-primary">عرض العائلة</a>
                    </td>
                </tr>
                @endforeach
                @if($orphans->isEmpty())
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #7f8c8d;">لا يوجد أيتام مسجلون</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $orphans->links('vendor.livewire.custom-pagination') }}
    </div>
</div>
