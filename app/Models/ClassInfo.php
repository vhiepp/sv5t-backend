<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;

class ClassInfo extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected static function boot ()
    {
        parent::boot();

        static::creating(function ($model) {
            $schoolYear = Str::substr($model['code'], 2, 2);
            $model->setAttribute('school_year', (int)$schoolYear);
        });
    }
}