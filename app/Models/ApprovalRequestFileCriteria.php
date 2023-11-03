<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;
use Illuminate\Support\Str;

class ApprovalRequestFileCriteria extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'file_name',
        'file_url',
        'approval_request_id',
        'requirement_criteria_id',
        'active',
        'qualified'
    ];

    protected $hidden = [
        'requirement_criteria_id',
        'active',
        'approval_request_id',
        'created_at',
        'updated_at',
    ];

    protected static function boot()
    {
        parent::boot();

        // event get data
        static::retrieved(function ($model) {
            if (!Str::isUrl($model->file_url)) {
                $model->file_url = env('APP_URL', 'http://localhost:8000') . $model->file_url;
            }
            $model->created_time = DateHelper::make($model->created_at);
            $model->updated_time = DateHelper::make($model->updated_at);
        });
    }

    public function approvalRequest(): HasOne
    {
        return $this->hasOne(ApprovalRequest::class, 'id', 'approval_request_id');
    }

    public function requirementCriteria(): HasOne
    {
        return $this->hasOne(RequirementCriteria::class, 'id', 'requirement_criteria_id');
    }

}