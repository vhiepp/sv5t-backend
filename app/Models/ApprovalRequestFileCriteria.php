<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequestFileCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_url',
        'approval_request_id',
        'requirement_criteria_id',
        'active'
    ];
}