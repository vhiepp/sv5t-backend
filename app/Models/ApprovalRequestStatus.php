<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\DateHelper;

class ApprovalRequestStatus extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'approval_request_id',
        'approved_by_user_id',
        'status',
        'date_approved'
    ];

    protected $hidden = [
        'approval_request_id',
        'approved_by_user_id'
    ];

    public function approvalRequest(): HasOne
    {
        return $this->hasOne(ApprovalRequest::class, 'id', 'approval_request_id');
    }

    public function approvedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'approved_by_user_id');
    }
}