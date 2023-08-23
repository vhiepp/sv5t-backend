<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Origin extends Model
{
    use HasFactory;

    protected $fillable = [
        'link', 'forum_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function forum(): BelongsTo
    {
        return $this->belongsTo(Forum::class);
    }


}