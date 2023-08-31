<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;

class Forum extends Model
{
    use HasFactory, Sluggable, HasUuids;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'active',
        'type',
        'user_id',
        'description'
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();

        // event get data
        static::retrieved(function ($model) {
            $model->creator;
            $model->origins;
            $model->created_time = DateHelper::make($model->created_at);
            $model->updated_time = DateHelper::make($model->updated_at);
        });
    }

    public function scopeGetWhereSlug(Builder $query, string $slug)
    {
        return $query->firstWhere('slug', $slug);
    }

    public function scopeDeleteActive($query) {
        return $query->update(['active' => -1]);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function creator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'forum_id', 'id');
    }

    public function hearts(): HasMany
    {
        return $this->hasMany(Heart::class, 'forum_id', 'id');
    }

    public function origins(): HasMany
    {
        return $this->hasMany(Origin::class, 'forum_id', 'id');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}