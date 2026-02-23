<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class PostManager extends Component
{
    use WithFileUploads;

    public $postId;
    public $title = '';
    public $type = 'food';
    public $priority = 0;
    public $address = '';
    public $content = '';
    public $is_active = true;

    public $featured_image;
    public $existing_featured_image = '';

    public $gallery_images = [];
    public $existing_gallery_images = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'type' => 'required|in:food,voluntary_work',
        'priority' => 'required|integer|min:0',
        'address' => 'nullable|string|max:500',
        'content' => 'nullable|string',
        'is_active' => 'boolean',
        'featured_image' => 'nullable|image|max:5120',
        'gallery_images.*' => 'nullable|image|max:5120',
    ];

    protected $messages = [
        'title.required' => 'عنوان المنشور مطلوب',
        'type.required' => 'نوع النشاط مطلوب',
        'priority.required' => 'الأولوية مطلوبة',
        'featured_image.image' => 'يجب أن تكون الصورة الرئيسية ملف صورة',
        'featured_image.max' => 'حجم الصورة الرئيسية يجب ألا يتجاوز 5 ميجابايت',
        'gallery_images.*.image' => 'يجب أن تكون ملفات المعرض صور',
        'gallery_images.*.max' => 'حجم كل صورة يجب ألا يتجاوز 5 ميجابايت',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $post = Post::with('images')->findOrFail($id);
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->type = $post->type;
            $this->priority = $post->priority;
            $this->address = $post->address ?? '';
            $this->content = $post->content ?? '';
            $this->is_active = $post->is_active;
            $this->existing_featured_image = $post->featured_image ?? '';
            $this->existing_gallery_images = $post->images->map(function ($img) {
                return ['id' => $img->id, 'path' => $img->image_path];
            })->toArray();
        }
    }

    public function removeExistingGalleryImage($imageId)
    {
        $image = PostImage::find($imageId);
        if ($image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
            $this->existing_gallery_images = array_values(
                array_filter($this->existing_gallery_images, fn($img) => $img['id'] !== $imageId)
            );
        }
    }

    public function removeExistingFeaturedImage()
    {
        if ($this->existing_featured_image && Storage::disk('public')->exists($this->existing_featured_image)) {
            Storage::disk('public')->delete($this->existing_featured_image);
        }
        if ($this->postId) {
            Post::where('id', $this->postId)->update(['featured_image' => null]);
        }
        $this->existing_featured_image = '';
    }

    public function save()
    {
        $this->validate();

        $slug = Post::generateUniqueSlug($this->title, $this->postId);

        $data = [
            'title' => $this->title,
            'slug' => $slug,
            'type' => $this->type,
            'priority' => $this->priority,
            'address' => $this->address,
            'content' => $this->content,
            'is_active' => $this->is_active,
        ];

        // Handle featured image upload
        if ($this->featured_image) {
            // Delete old featured image
            if ($this->existing_featured_image && Storage::disk('public')->exists($this->existing_featured_image)) {
                Storage::disk('public')->delete($this->existing_featured_image);
            }
            $data['featured_image'] = $this->featured_image->store('posts/featured', 'public');
        }

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update($data);
        } else {
            $post = Post::create($data);
        }

        // Handle gallery images upload
        if (!empty($this->gallery_images)) {
            $maxSort = $post->images()->max('sort_order') ?? 0;
            foreach ($this->gallery_images as $index => $image) {
                $path = $image->store('posts/gallery', 'public');
                PostImage::create([
                    'post_id' => $post->id,
                    'image_path' => $path,
                    'sort_order' => $maxSort + $index + 1,
                ]);
            }
        }

        $this->dispatch('swal', [
            'title' => 'تم الحفظ!',
            'text' => $this->postId ? 'تم تحديث المنشور بنجاح.' : 'تم إنشاء المنشور بنجاح.',
            'icon' => 'success'
        ]);

        return redirect()->route('dashboard.posts');
    }

    public function render()
    {
        return view('livewire.post-manager');
    }
}
