<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class PostList extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function deletePost($id)
    {
        $post = Post::find($id);
        if ($post) {
            // Delete featured image
            if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
                Storage::disk('public')->delete($post->featured_image);
            }
            // Delete gallery images
            foreach ($post->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            $post->delete();

            $this->dispatch('swal', [
                'title' => 'تم الحذف!',
                'text' => 'تم حذف المنشور بنجاح.',
                'icon' => 'success'
            ]);
        }
    }

    public function toggleStatus($id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->update(['is_active' => !$post->is_active]);
        }
    }

    public function render()
    {
        $query = Post::query()->with('images');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('address', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter !== '') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $posts = $query->ordered()->paginate(10);

        return view('livewire.post-list', [
            'posts' => $posts,
        ]);
    }
}
