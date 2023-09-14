<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\DateHelper;

class RequirementCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'active',
        'required'
    ];

    protected $hidden = [
        'active',
        'required',
        'created_at',
        'updated_at',
    ];
}