<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApprovalRequestStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_request_id',
        'user_id',
        'status',
        'date_approved'
    ];

    protected $hidden = [
        'approval_request_id',
        'user_id'
    ];

    public function approvalRequest(): HasOne
    {
        return $this->hasOne(ApprovalRequest::class);
    }

    public function approvedBy(): HasOne
    {
        return $this->hasOne(User::class);
    }
}