<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approval_id',
        'active'
    ];

    protected $hidden = ['approval_id', 'user_id'];

    public function requestSender(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function approval(): HasOne
    {
        return $this->hasOne(Approval::class);
    }

    public function status(): HasOne
    {
        return $this->hasOne(ApprovalRequestStatus::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ApprovalRequestFileCriteria::class);
    }
}