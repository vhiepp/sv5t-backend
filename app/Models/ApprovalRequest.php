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
        'active'
    ];

    protected $hidden = ['approval_id', 'user_id'];

    public function requestSender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function approval(): HasOne
    {
        return $this->hasOne(Approval::class, 'id', 'approval_id');
    }

    public function status(): HasOne
    {
        return $this->hasOne(ApprovalRequestStatus::class, 'approval_request_id', 'id');
    }

    public function requireDetail(): HasMany
    {
        return $this->hasMany(ApprovalRequestFileCriteria::class, 'approval_request_id', 'id');
    }
}