<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_start',
        'date_end',
        'status',
        'user_id'
    ];

    protected $hidden = [
        'user_id'
    ];

    public function creator(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class);
    }
}