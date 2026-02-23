<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'type', 'priority', 'address',
        'featured_image', 'content', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function images()
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('priority', 'asc')->orderBy('created_at', 'desc');
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'food' => 'طعام',
            'voluntary_work' => 'عمل تطوعي',
            default => $this->type,
        };
    }

    public function getShareUrlAttribute()
    {
        return url('/activities/' . $this->slug);
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public static function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        if (empty($slug)) {
            $slug = Str::random(8);
        }

        $original = $slug;
        $count = 1;

        while (static::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
