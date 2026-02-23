<div>
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3>{{ $postId ? 'تعديل المنشور' : 'إضافة منشور جديد' }}</h3>
            <a href="{{ route('dashboard.posts') }}" class="btn" style="background: #95a5a6; color: white;">
                <i class="fas fa-arrow-right"></i> العودة للقائمة
            </a>
        </div>

        <form wire:submit.prevent="save">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                {{-- Title --}}
                <div style="grid-column: span 2;">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">العنوان <span style="color: #e74c3c;">*</span></label>
                    <input type="text" wire:model="title" placeholder="عنوان المنشور">
                    @error('title') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">نوع النشاط <span style="color: #e74c3c;">*</span></label>
                    <select wire:model="type">
                        <option value="food">طعام</option>
                        <option value="voluntary_work">عمل تطوعي</option>
                    </select>
                    @error('type') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                {{-- Priority --}}
                <div>
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">الأولوية <span style="color: #e74c3c;">*</span></label>
                    <input type="number" wire:model="priority" min="0" placeholder="0 = الأعلى أولوية">
                    @error('priority') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                {{-- Address --}}
                <div style="grid-column: span 2;">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">العنوان / الموقع</label>
                    <input type="text" wire:model="address" placeholder="موقع النشاط">
                    @error('address') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">الحالة</label>
                    <select wire:model="is_active">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>

                {{-- Featured Image --}}
                <div>
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">الصورة الرئيسية</label>
                    <input type="file" wire:model="featured_image" accept="image/*">
                    @error('featured_image') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror

                    <div wire:loading wire:target="featured_image" style="margin-top: 5px; color: #3498db; font-size: 0.85rem;">
                        <i class="fas fa-spinner fa-spin"></i> جاري رفع الصورة...
                    </div>

                    @if($featured_image)
                        <div style="margin-top: 10px;">
                            <img src="{{ $featured_image->temporaryUrl() }}" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        </div>
                    @elseif($existing_featured_image)
                        <div style="margin-top: 10px; position: relative; display: inline-block;">
                            <img src="{{ asset('storage/' . $existing_featured_image) }}" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <button type="button" wire:click="removeExistingFeaturedImage" wire:confirm="هل تريد حذف الصورة الرئيسية؟"
                                    style="position: absolute; top: -8px; right: -8px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-size: 0.7rem;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Gallery Images --}}
                <div style="grid-column: span 2;">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">معرض الصور</label>
                    <input type="file" wire:model="gallery_images" accept="image/*" multiple>
                    @error('gallery_images.*') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror

                    <div wire:loading wire:target="gallery_images" style="margin-top: 5px; color: #3498db; font-size: 0.85rem;">
                        <i class="fas fa-spinner fa-spin"></i> جاري رفع الصور...
                    </div>

                    {{-- New gallery preview --}}
                    @if($gallery_images)
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                            @foreach($gallery_images as $img)
                                <img src="{{ $img->temporaryUrl() }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            @endforeach
                        </div>
                    @endif

                    {{-- Existing gallery images --}}
                    @if(!empty($existing_gallery_images))
                        <div style="margin-top: 10px;">
                            <label style="font-size: 0.9rem; color: #666;">الصور الحالية:</label>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 5px;">
                                @foreach($existing_gallery_images as $img)
                                    <div style="position: relative; display: inline-block;">
                                        <img src="{{ asset('storage/' . $img['path']) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        <button type="button" wire:click="removeExistingGalleryImage({{ $img['id'] }})" wire:confirm="هل تريد حذف هذه الصورة؟"
                                                style="position: absolute; top: -6px; right: -6px; background: #e74c3c; color: white; border: none; border-radius: 50%; width: 22px; height: 22px; cursor: pointer; font-size: 0.65rem;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Content (CKEditor) --}}
                <div style="grid-column: span 2;">
                    <label style="font-weight: bold; margin-bottom: 5px; display: block;">المحتوى</label>
                    <div wire:ignore>
                        <textarea id="ckeditor-content" wire:model="content">{!! $content !!}</textarea>
                    </div>
                    @error('content') <span style="color: #e74c3c; font-size: 0.85rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div style="margin-top: 30px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 40px; font-size: 1rem;">
                    <span wire:loading.remove wire:target="save">
                        <i class="fas fa-save"></i> {{ $postId ? 'تحديث المنشور' : 'حفظ المنشور' }}
                    </span>
                    <span wire:loading wire:target="save">
                        <i class="fas fa-spinner fa-spin"></i> جاري الحفظ...
                    </span>
                </button>
                <a href="{{ route('dashboard.posts') }}" class="btn" style="background: #95a5a6; color: white; padding: 12px 40px; font-size: 1rem;">
                    إلغاء
                </a>
            </div>
        </form>
    </div>

    {{-- CKEditor CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editorElement = document.querySelector('#ckeditor-content');
            if (editorElement && !editorElement.classList.contains('ck-editor__editable')) {
                ClassicEditor
                    .create(editorElement, {
                        language: 'ar',
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'underline', 'strikethrough', '|',
                            'bulletedList', 'numberedList', '|',
                            'alignment', '|',
                            'link', 'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ],
                    })
                    .then(editor => {
                        // Sync CKEditor content with Livewire
                        editor.model.document.on('change:data', () => {
                            @this.set('content', editor.getData());
                        });
                    })
                    .catch(error => {
                        console.error('CKEditor Error:', error);
                    });
            }
        });
    </script>
</div>
