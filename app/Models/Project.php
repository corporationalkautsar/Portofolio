<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'stack',
        'thumbnail',
        'project_image',
        'project_url',
        'year',
        'featured',
        'status',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'year' => 'integer',
    ];

    protected $appends = ['thumbnail_url', 'project_image_url'];

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            if (blank($project->slug)) {
                $project->slug = Str::slug($project->title).'-'.Str::lower(Str::random(4));
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->thumbnail);
    }

    public function getProjectImageUrlAttribute(): string
    {
        return $this->resolveImageUrl($this->project_image ?: $this->thumbnail);
    }

    private function resolveImageUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/placeholders/project-01.svg');
        }

        return str_starts_with($path, 'images/placeholders/')
            ? asset($path)
            : Storage::disk('public')->url($path);
    }
}
