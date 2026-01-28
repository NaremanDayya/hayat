<div>
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--accent); padding-bottom: 10px; margin-bottom: 30px;">
        <h2>تفاصيل عائلة: {{ $family->husband_name }}</h2>
        <div style="display: flex; gap: 10px;">
            <a href="/families" class="btn btn-secondary" style="background: #34495e; color: white;">رجوع للقائمة</a>
            <button wire:click="deleteFamily" wire:confirm="هل أنت متأكد من حذف هذه العائلة؟" class="btn" style="background: #e74c3c; color: white;">حذف العائلة</button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Parents Information -->
        <div class="card" style="background: #fff;">
            <h3>بيانات الوالدين</h3>
            <table class="details-table">
                <tr>
                    <td style="font-weight: bold; width: 40%;">اسم الزوج:</td>
                    <td>{{ $family->husband_name }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">رقم هوية الزوج:</td>
                    <td>{{ $family->husband_id_number }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">تاريخ ميلاد الزوج:</td>
                    <td>{{ $family->husband_dob }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">هاتف الزوج:</td>
                    <td>{{ $family->husband_phone }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">الحالة الاجتماعية:</td>
                    <td><span class="badge" style="background: #3498db; color: white;">{{ $family->marital_status }}</span></td>
                </tr>
                <tr style="border-top: 2px solid #eee;">
                    <td style="font-weight: bold;">اسم الزوجة:</td>
                    <td>{{ $family->wife_name }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">رقم هوية الزوجة:</td>
                    <td>{{ $family->wife_id_number }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">تاريخ ميلاد الزوجة:</td>
                    <td>{{ $family->wife_dob }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">هاتف الزوجة:</td>
                    <td>{{ $family->wife_phone }}</td>
                </tr>
            </table>
        </div>

        <!-- Residence and Stats -->
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="card" style="background: #fff; flex: 1;">
                <h3>بيانات السكن</h3>
                <p><strong>السكن الأصلي:</strong> {{ $family->original_address }}</p>
                <p><strong>السكن الحالي:</strong> {{ $family->current_address }}</p>
            </div>
            <div class="card" style="background: var(--accent); color: white; flex: 1; text-align: center;">
                <h3>إجمالي عدد الأفراد</h3>
                <div style="font-size: 3rem; font-weight: bold;">{{ $family->family_members_count }}</div>
            </div>
        </div>
    </div>

    <!-- Children Sections -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px;">
        <div class="card" style="border-top: 5px solid #3498db;">
            <h3>الأبناء الذكور</h3>
            <table>
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>رقم الهوية</th>
                        <th>العمر</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($family->members->where('gender', 'male') as $son)
                    <tr>
                        <td>{{ $son->name }}</td>
                        <td>{{ $son->id_number }}</td>
                        <td>{{ $son->age }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card" style="border-top: 5px solid #e91e63;">
            <h3>الأبناء الإناث</h3>
            <table>
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>رقم الهوية</th>
                        <th>العمر</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($family->members->where('gender', 'female') as $daughter)
                    <tr>
                        <td>{{ $daughter->name }}</td>
                        <td>{{ $daughter->id_number }}</td>
                        <td>{{ $daughter->age }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Health Conditions -->
    @if($family->healthConditions->count() > 0)
    <div class="card" style="margin-top: 30px; border: 2px solid #e74c3c;">
        <h3 style="color: #e74c3c;">الحالات الصحية والخاصة</h3>
        <table>
            <thead>
                <tr>
                    <th>اسم الشخص</th>
                    <th>طبيعة الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($family->family_health_conditions ?? $family->healthConditions as $condition)
                <tr>
                    <td>{{ $condition->person_name }}</td>
                    <td>{{ $condition->condition_details }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<style>
    .details-table td {
        padding: 10px;
        border-bottom: none;
    }
</style>
</div>
