<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slogan',
        'logo',
        'leader_user_id'
    ];

    protected $hidden = ['leader_user_id'];

    public function leader(): HasOne
    {
        return $this->hasOne(User::class, 'id');
    }
}