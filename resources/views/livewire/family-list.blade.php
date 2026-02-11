<div class="card">
    <div class="flex-between" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3>بيانات العائلات</h3>
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث في كل البيانات..." style="width: 300px;">
            
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
                <button @click="open = !open" class="btn btn-primary">
                    <i class="fas fa-filter"></i> فلترة العمر
                </button>
                <div x-show="open" @click.away="open = false" class="card" style="position: absolute; top: 100%; left: 0; z-index: 100; width: 250px; background: white; margin-top: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    <label>العمر من:</label>
                    <input type="number" wire:model.live="minAge" placeholder="أقل عمر">
                    
                    <label style="margin-top:10px; display:block;">إلى:</label>
                    <input type="number" wire:model.live="maxAge" placeholder="أكبر عمر">
                    
                    <button @click="open = false" class="btn btn-primary" style="margin-top: 10px; width: 100%;">تطبيق</button>
                    <button wire:click="resetFilters" @click="open = false" class="btn" style="margin-top: 5px; width: 100%; background: #eee;">تفريغ</button>
                </div>
            </div>

            <div style="position: relative;" x-data="{ open: false }">
                <button @click="open = !open" class="btn btn-primary">
                    <i class="fas fa-users"></i> عدد الأفراد
                </button>
                <div x-show="open" @click.away="open = false" class="card" style="position: absolute; top: 100%; left: 0; z-index: 100; width: 250px; background: white; margin-top: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    <label>العدد من:</label>
                    <input type="number" wire:model.live="minMembers" placeholder="أقل عدد">
                    
                    <label style="margin-top:10px; display:block;">إلى:</label>
                    <input type="number" wire:model.live="maxMembers" placeholder="أكبر عدد">
                    
                    <button @click="open = false" class="btn btn-primary" style="margin-top: 10px; width: 100%;">تطبيق</button>
                    <button wire:click="resetFilters" @click="open = false" class="btn" style="margin-top: 5px; width: 100%; background: #eee;">تفريغ</button>
                </div>
            </div>

            <label style="display: flex; align-items: center; gap: 5px; cursor: pointer;">
                <input type="checkbox" wire:model.live="hasDisease" style="width: auto;"> حالات صحية
            </label>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>
                        <div style="display: flex; align-items: center; gap: 8px; justify-content: center;">
                            <span>اسم الزوج</span>
                            <button wire:click="sortByName" style="background: transparent; border: none; cursor: pointer; padding: 4px 8px; border-radius: 4px; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                                @if($sortBy === 'name_asc')
                                    <i class="fas fa-sort-alpha-down" style="color: #3498db;"></i>
                                @elseif($sortBy === 'name_desc')
                                    <i class="fas fa-sort-alpha-up" style="color: #3498db;"></i>
                                @else
                                    <i class="fas fa-sort" style="color: #95a5a6;"></i>
                                @endif
                            </button>
                        </div>
                    </th>
                    <th>الحالة</th>
                    <th>رقم هوية الزوج</th>
                    <th>اسم الزوجة</th>
                    <th>رقم هوية الزوجة</th>
                    <th>عدد الأفراد</th>
                    <th>السكن الأصلي</th>
                    <th>السكن الحالي</th>
                    <th>حالات خاصة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($families as $family)
                <tr>
                    <td>{{ $family->husband_name }}</td>
                    <td><span class="badge" style="background: #34495e; color: white; font-size: 0.7rem;">{{ $family->marital_status ?? 'متزوج' }}</span></td>
                    <td>
                        <div style="font-weight: bold;">{{ $family->husband_id_number }}</div>
                        <div style="font-size: 0.8rem; color: #666;">{{ $family->husband_dob ? \Carbon\Carbon::parse($family->husband_dob)->translatedFormat('j F Y') : '-' }}</div>
                    </td>
                    <td>{{ $family->wife_name }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $family->wife_id_number }}</div>
                        <div style="font-size: 0.8rem; color: #666;">{{ $family->wife_dob ? \Carbon\Carbon::parse($family->wife_dob)->translatedFormat('j F Y') : '-' }}</div>
                    </td>
                    <td>{{ $family->calculated_members_count }}</td>
                    <td>{{ $family->original_address }}</td>
                    <td>{{ $family->current_address }}</td>
                    <td>
                        @if($family->healthConditions->count() > 0)
                            <span class="badge" style="background: #e74c3c; color: white;">نعم</span>
                            <div style="font-size: 0.75rem; color: #e74c3c; margin-top: 5px;">
                                @foreach($family->healthConditions as $condition)
                                    <div>• {{ $condition->condition_details }}</div>
                                @endforeach
                            </div>
                        @else
                            <span class="badge" style="background: #2ecc71; color: white;">لا</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('dashboard.family-details', $family->id) }}" class="btn btn-primary" style="padding: 5px 10px;">عرض</a>
                            <a href="{{ route('dashboard.edit-family', $family->id) }}" class="btn" style="background: #f1c40f; color: white; padding: 5px 10px;">تعديل</a>
                            <button wire:click="deleteFamily({{ $family->id }})" wire:confirm="هل أنت متأكد من حذف هذه العائلة؟" class="btn" style="background: #e74c3c; color: white; padding: 5px 10px;">حذف</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $families->links('vendor.livewire.custom-pagination') }}
    </div>
</div>
