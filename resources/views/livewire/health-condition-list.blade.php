<div class="card">
    <div class="flex-between" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>الحالات الصحية الخاصة</h3>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث في الاسم أو الحالة..." style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
            
            <select wire:model.live="gender" style="padding: 8px; border-radius: 5px; border: 1px solid #ddd; background-color: white;">
                <option value="">جميع الأجناس</option>
                <option value="male">ذكر</option>
                <option value="female">أنثى</option>
            </select>
        </div>
    </div>

    <div class="table-container" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; min-width: 1200px;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">اسم الفرد</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">رقم الهوية</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">الجنس</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">تاريخ الميلاد</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">العمر</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">رقم الهاتف</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">العنوان الأصلي</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">العنوان الحالي</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">الحالة الصحية</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 2px solid #ddd;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($healthConditions as $condition)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 12px;">{{ $condition->person_name }}</td>
                    <td style="padding: 12px;">{{ $condition->id_number ?? '-' }}</td>
                    <td style="padding: 12px;">
                        @if($condition->gender === 'male')
                            <span style="background: #3498db; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">ذكر</span>
                        @elseif($condition->gender === 'female')
                            <span style="background: #e91e63; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">أنثى</span>
                        @else
                            <span style="background: #95a5a6; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.85em;">-</span>
                        @endif
                    </td>
                    <td style="padding: 12px;">{{ $condition->date_of_birth ? \Carbon\Carbon::parse($condition->date_of_birth)->format('Y-m-d') : '-' }}</td>
                    <td style="padding: 12px;">{{ $condition->age ?? '-' }}</td>
                    <td style="padding: 12px;">{{ $condition->phone ?? '-' }}</td>
                    <td style="padding: 12px; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $condition->original_address }}">{{ $condition->original_address ?? '-' }}</td>
                    <td style="padding: 12px; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $condition->current_address }}">{{ $condition->current_address ?? '-' }}</td>
                    <td style="padding: 12px; max-width: 200px;">
                        <div style="max-height: 60px; overflow-y: auto;">
                            {{ $condition->condition_details }}
                        </div>
                    </td>
                    <td style="padding: 12px;">
                        @if($condition->family_id)
                            <a href="{{ route('dashboard.family-details', $condition->family_id) }}" class="btn btn-primary" style="text-decoration: none; display: inline-block;">عرض العائلة</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($healthConditions->isEmpty())
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px; color: #7f8c8d;">لا توجد حالات مسجلة</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $healthConditions->links('vendor.livewire.custom-pagination') }}
    </div>
</div>
