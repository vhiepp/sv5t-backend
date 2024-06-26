<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;

class Origin extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'link', 'forum_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function forum(): HasOne
    {
        return $this->hasOne(Forum::class, 'id', 'forum_id');
    }

}