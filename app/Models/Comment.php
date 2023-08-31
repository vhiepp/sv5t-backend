<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;

class Comment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'forum_id',
        'content',
        'active'
    ];

    protected $hidden = [
        'user_id',
        'forum_id',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        // event get data
        static::retrieved(function ($model) {
            $model->user;
            $model->created_time = DateHelper::make($model->created_at);
            $model->updated_time = DateHelper::make($model->updated_at);
        });
    }

    public function forum(): HasOne
    {
        return $this->hasOne(Forum::class, 'id', 'forum_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeDeleteActive($query) {
        return $query->update(['active' => -1]);
    }

}
