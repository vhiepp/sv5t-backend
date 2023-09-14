<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;

class Approval extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'date_start',
        'date_end',
        'user_id'
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();
        // event get data
        static::retrieved(function ($model) {
            $nowDate = date('Y-m-d H:i:s');
            if ($nowDate > $model->date_end) {
                $model->status = 'finished';
            } elseif ($nowDate < $model->date_start) {
                $model->status = 'upcoming';
            } elseif ($nowDate >= $model->date_start && $nowDate <= $model->date_end) {
                $model->status = 'happenning';
            }
            $model->date_start = date('d/m/Y', strtotime($model['date_start']));
            $model->date_end = date('d/m/Y', strtotime($model['date_end']));
        });
    }

    public function creator(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ApprovalRequest::class, 'approval_id', 'id');
    }
}
