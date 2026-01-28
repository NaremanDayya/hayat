<div class="card">
    <h2 style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid var(--accent); padding-bottom: 10px;">
        {{ $familyId ? 'تعديل بيانات عائلة: ' . $husband_name : 'إضافة عائلة جديدة للمخيم' }}
    </h2>

    <form wire:submit.prevent="save">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Husband Section -->
            <div class="card" style="background: rgba(44, 62, 80, 0.05);">
                <h3>بيانات الزوج</h3>
                <label>اسم الزوج</label>
                <input type="text" wire:model="husband_name">
                
                <label>رقم هوية الزوج</label>
                <input type="text" wire:model="husband_id_number">
                
                <label>تاريخ ميلاد الزوج</label>
                <input type="date" wire:model="husband_dob">
                
                <label>رقم هاتف الزوج</label>
                <input type="text" wire:model="husband_phone">

                <label>الحالة الاجتماعية للزوج</label>
                <select wire:model="marital_status">
                    <option value="متزوج">متزوج</option>
                    <option value="مطلق">مطلق</option>
                    <option value="أرمل">أرمل</option>
                </select>
            </div>

            <!-- Wife Section -->
            <div class="card" style="background: rgba(233, 30, 99, 0.05);">
                <h3>بيانات الزوجة</h3>
                <label>اسم الزوجة</label>
                <input type="text" wire:model="wife_name">
                
                <label>رقم هوية الزوجة</label>
                <input type="text" wire:model="wife_id_number">
                
                <label>تاريخ ميلاد الزوجة</label>
                <input type="date" wire:model="wife_dob">
                
                <label>رقم هاتف الزوجة</label>
                <input type="text" wire:model="wife_phone">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
            <!-- Residence -->
            <div class="card">
                <h3>بيانات السكن</h3>
                <label>مكان السكن الأصلي</label>
                <textarea wire:model="original_address"></textarea>
                
                <label>مكان السكن الحالي</label>
                <textarea wire:model="current_address"></textarea>
            </div>

            <!-- Summary -->
            <div class="card">
                <h3>ملخص</h3>
                <label>عدد أفراد الأسرة المقدر</label>
                <input type="number" wire:model="members_count" readonly style="background: #eee;">
                <p style="font-size: 0.9rem; color: #666;">* يتم احتسابه تلقائياً (الوالدين + الأبناء)</p>
            </div>
        </div>

        <!-- Sons Section -->
        <div class="card" style="margin-top: 20px; border-right: 5px solid #3498db;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3>الأبناء الذكور</h3>
                <button type="button" wire:click="addSon" class="btn btn-primary">إضافة ابن +</button>
            </div>
            
            @foreach($sons as $index => $son)
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 0.5fr; gap: 10px; margin-top: 10px; align-items: end;">
                <div>
                    <label>الاسم</label>
                    <input type="text" wire:model="sons.{{ $index }}.name">
                </div>
                <div>
                    <label>رقم الهوية</label>
                    <input type="text" wire:model="sons.{{ $index }}.id_number">
                </div>
                <div>
                    <label>تاريخ الميلاد</label>
                    <input type="date" wire:model="sons.{{ $index }}.dob">
                </div>
                <button type="button" wire:click="removeSon({{ $index }})" class="btn" style="background: #e74c3c; color: white;">حذف</button>
            </div>
            @endforeach
        </div>

        <!-- Daughters Section -->
        <div class="card" style="margin-top: 20px; border-right: 5px solid #e91e63;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3>الأبناء الإناث</h3>
                <button type="button" wire:click="addDaughter" class="btn" style="background: #e91e63; color: white;">إضافة ابنة +</button>
            </div>
            
            @foreach($daughters as $index => $daughter)
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 0.5fr; gap: 10px; margin-top: 10px; align-items: end;">
                <div>
                    <label>الاسم</label>
                    <input type="text" wire:model="daughters.{{ $index }}.name">
                </div>
                <div>
                    <label>رقم الهوية</label>
                    <input type="text" wire:model="daughters.{{ $index }}.id_number">
                </div>
                <div>
                    <label>تاريخ الميلاد</label>
                    <input type="date" wire:model="daughters.{{ $index }}.dob">
                </div>
                <button type="button" wire:click="removeDaughter({{ $index }})" class="btn" style="background: #e74c3c; color: white;">حذف</button>
            </div>
            @endforeach
        </div>

        <!-- Health Conditions -->
        <div class="card" style="margin-top: 20px; border-right: 5px solid #f1c40f;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3>حالات صحية / إصابات / إعاقات</h3>
                <button type="button" wire:click="addHealthCondition" class="btn" style="background: #f1c40f; color: white;">إضافة حالة +</button>
            </div>
            
            @foreach($health_conditions as $index => $condition)
            <div style="display: grid; grid-template-columns: 1fr 2fr 0.5fr; gap: 10px; margin-top: 10px; align-items: end;">
                <div>
                    <label>اسم الشخص</label>
                    <input type="text" wire:model="health_conditions.{{ $index }}.person_name">
                </div>
                <div>
                    <label>طبيعة الحالة/المرض/الإعاقة</label>
                    <input type="text" wire:model="health_conditions.{{ $index }}.details">
                </div>
                <button type="button" wire:click="removeHealthCondition({{ $index }})" class="btn" style="background: #e74c3c; color: white;">حذف</button>
            </div>
            @endforeach
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <button type="submit" class="btn btn-primary" style="padding: 15px 50px; font-size: 1.2rem;">حفظ بيانات العائلة</button>
        </div>
    </form>
</div>
