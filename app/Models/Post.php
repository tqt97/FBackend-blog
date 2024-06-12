<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\Comment;
use App\Models\Category;
use App\Enums\PostStatus;
use App\Models\SeoDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'sub_title',
        'body',
        'status',
        'published_at',
        'scheduled_for',
        'cover_photo_path',
        'photo_alt_text',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'scheduled_for' => 'datetime',
            'status' => PostStatus::class,
            'user_id' => 'integer',
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsApprove(): HasMany
    {
        return $this->hasMany(Comment::class)->whereApproved(true);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seoDetail()
    {
        return $this->hasOne(SeoDetail::class);
    }

    public function isNotPublished()
    {
        return !$this->isStatusPublished();
    }

    public function scopePublished(Builder $query)
    {
        return $query->where('status', PostStatus::PUBLISHED)->latest('published_at');
    }

    public function scopeScheduled(Builder $query)
    {
        return $query->where('status', PostStatus::SCHEDULED)->latest('scheduled_for');
    }

    public function scopePending(Builder $query)
    {
        return $query->where('status', PostStatus::PENDING)->latest('created_at');
    }

    public function formattedPublishedDate()
    {
        return $this->published_at?->format('d M Y');
    }

    public function isScheduled()
    {
        return $this->status === PostStatus::SCHEDULED;
    }

    public function isStatusPublished()
    {
        return $this->status === PostStatus::PUBLISHED;
    }

    public function relatedPosts($take = 3)
    {
        return $this->whereHas('categories', function ($query) {
            $query->whereIn('categories.id', $this->categories->pluck('id'))
                ->whereNotIn('posts.id', [$this->id]);
        })->published()->with('user')->take($take)->get();
    }

    protected function getFeaturePhotoAttribute()
    {
        return asset('storage/' . $this->cover_photo_path);
    }
}
