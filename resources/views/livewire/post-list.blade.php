<div class="card">
    <div class="flex-between" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
        <h3>إدارة المنشورات والأنشطة</h3>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث في المنشورات..." style="width: 250px;">

            <select wire:model.live="typeFilter" style="width: 160px;">
                <option value="">كل الأنواع</option>
                <option value="food">طعام</option>
                <option value="voluntary_work">عمل تطوعي</option>
            </select>

            <select wire:model.live="statusFilter" style="width: 140px;">
                <option value="">كل الحالات</option>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
            </select>

            <a href="{{ route('dashboard.create-post') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> إضافة منشور
            </a>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">الأولوية</th>
                    <th style="width: 80px;">الصورة</th>
                    <th>العنوان</th>
                    <th>النوع</th>
                    <th>العنوان/الموقع</th>
                    <th>الحالة</th>
                    <th>المعرض</th>
                    <th>التاريخ</th>
                    <th style="width: 200px;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td style="text-align: center; font-weight: bold; font-size: 1.1rem;">{{ $post->priority }}</td>
                    <td>
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                 style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        @else
                            <div style="width: 60px; height: 60px; background: #ecf0f1; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="color: #bdc3c7; font-size: 1.2rem;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: bold;">{{ $post->title }}</div>
                        <div style="font-size: 0.8rem; color: #666; margin-top: 3px;">{{ Str::limit(strip_tags($post->content), 60) }}</div>
                    </td>
                    <td>
                        @if($post->type === 'food')
                            <span class="badge" style="background: #e67e22; color: white;">طعام</span>
                        @else
                            <span class="badge" style="background: #27ae60; color: white;">عمل تطوعي</span>
                        @endif
                    </td>
                    <td style="font-size: 0.9rem;">{{ $post->address ?? '-' }}</td>
                    <td>
                        <button wire:click="toggleStatus({{ $post->id }})" class="btn" style="padding: 4px 12px; font-size: 0.8rem; {{ $post->is_active ? 'background: #2ecc71; color: white;' : 'background: #e74c3c; color: white;' }}">
                            {{ $post->is_active ? 'نشط' : 'غير نشط' }}
                        </button>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge" style="background: #3498db; color: white;">{{ $post->images->count() }} صور</span>
                    </td>
                    <td style="font-size: 0.85rem; color: #666;">{{ $post->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('dashboard.edit-post', $post->id) }}" class="btn" style="background: #f1c40f; color: white; padding: 5px 10px;">
                                <i class="fas fa-edit"></i> تعديل
                            </a>
                            <button wire:click="deletePost({{ $post->id }})" wire:confirm="هل أنت متأكد من حذف هذا المنشور؟" class="btn" style="background: #e74c3c; color: white; padding: 5px 10px;">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                            <a href="{{ url('/activities/' . $post->slug) }}" target="_blank" class="btn" style="background: #3498db; color: white; padding: 5px 10px;">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                        لا توجد منشورات حالياً
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $posts->links('vendor.livewire.custom-pagination') }}
    </div>
</div>
