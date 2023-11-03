<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;

class ApprovalRequest extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'approval_id',
        'approved_by_user_id',
        'status',
        'active'
    ];

    protected $hidden = [
        'approval_id',
        'user_id',
        'active',
        'approved_by_user_id',
        'created_at',
        'updated_at',

    ];

    protected static function boot()
    {
        parent::boot();

        // event create data
        static::creating(function ($model) {

            if (auth()->check()) {
                $model->setAttribute('user_id', auth()->user()['id']);
            }

            $approvalHappenning = Approval::happenning()->first();
            $model->setAttribute('approval_id', $approvalHappenning['id']);
        });

        static::retrieved(function ($model) {
            $model->requestSender;
            $model->approvedByUser;

            $model->created_time = DateHelper::make($model->created_at);
            $model->updated_time = DateHelper::make($model->updated_at);
        });
    }

    public function requestSender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function approval(): HasOne
    {
        return $this->hasOne(Approval::class, 'id', 'approval_id');
    }

    public function approvedByUser(): HasOne
    {
        return $this->hasOne(Approval::class, 'id', 'approved_by_user_id');
    }

    public function requireDetail(): HasMany
    {
        return $this->hasMany(ApprovalRequestFileCriteria::class, 'approval_request_id', 'id');
    }


}