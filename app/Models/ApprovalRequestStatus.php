<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequestStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_request_id',
        'user_id',
        'status',
        'date_approved'
    ];
}