<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Fillable(['title', 'author_id', 'slug', 'excerpt', 'content', 'status', 'visibility', 'published_at'])]
class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
            'visibility' => PostVisibility::class,
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function segments()
    {
        return $this->belongsToMany(Segment::class);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }
}
