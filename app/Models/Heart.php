<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Heart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'forum_id',
        'active'
    ];

    public function forum(): HasOne
    {
        return $this->hasOne(Forum::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}