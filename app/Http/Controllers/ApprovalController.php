<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;

class ApprovalController extends Controller
{
    public function getDetail() {
        $approval = Approval::where('date_end', Approval::max('date_end'))->first();
        if ($approval) {
            $approval->is_send_require = false;
            $approval->user = null;
            $approval->require_detail_is_send = null;
            if (auth()->check()) {
                $approval->user = auth()->user()->toArray();
                $approvalRequest = auth()->user()->approvalRequest;
                if ($approvalRequest = $approvalRequest->where('approval_id', $approval->id)->first()) {
                    $approval->is_send_require = true;
                    $approval->require_detail_is_send = $approvalRequest->requireDetail->groupBy('requirement_criteria_id');
                }
            }
        } else {
            $approval = [
                'status' => 'notopen',
                'is_send_require' => false,
                'user' => auth()->check() ? auth()->user()->toArray() : null,
                'require_detail_is_send' => null,
            ];
        }

        return response($approval);
    }
}