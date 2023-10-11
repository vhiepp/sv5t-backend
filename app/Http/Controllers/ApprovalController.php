<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\ApprovalRequest;
use App\Models\ApprovalRequestFileCriteria;
use Illuminate\Support\Str;

class ApprovalController extends Controller
{
    public function approvalDetail() {
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
        return $approval;
    }

    public function getDetail() {
        $approval = $this->approvalDetail();
        return response($approval);
    }

    public function sendRequirementCriteria(Request $request) {
        try {
            $files = $request->file();
        $approvalRequest = ApprovalRequest::create();
        foreach ($files as $key => $file) {

            $require = str()->of($key)->explode('_');
            $requireId = $require[0] . '_' . $require[1];            
            $uploadPath = public_path('uploads/requests/' . auth()->user()['slug']);

            $fileName = Str::replaceLast('.' . $file->extension(), '-' . rand(1111, 9999) . '.' . $file->extension(), date('dmY-His') . '_' . $file->getClientOriginalName());

            if ($file->move($uploadPath, $fileName)) {
                ApprovalRequestFileCriteria::create([
                    'approval_request_id' => $approvalRequest->id,
                    'file_name' => $fileName,
                    'file_url' => $uploadPath . '/' . $fileName,
                    'requirement_criteria_id' => $requireId
                ]);
            }

        }
        return response()->json([
            'status' => 'success',
            'error' => false,
            'approval_detail' => $this->approvalDetail()
        ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'error' => true,
                'approval_detail' => null
            ]);
        }
        
    }
} 