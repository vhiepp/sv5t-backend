<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;

class Unit extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slogan',
        'logo',
        'leader_user_id'
    ];

    protected $hidden = [
        'leader_user_id',
        'created_at',
        'updated_at',
        'slogan',
        'logo',
    ];

    public function leader(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'leader_user_id');
    }
}