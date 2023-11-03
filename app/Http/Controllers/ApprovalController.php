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
        $approval = Approval::get();

        if ($approval->count() > 0) {
            $approval = Approval::lastest()->first();
            if ($approval) {
                $approval->user = null;
                $approval->is_send_request = false;
                $approval->request_detail_is_send = null;
    
                if (auth()->check()) {
                    $approval->user = auth()->user()->toArray();
                    $approvalRequest = auth()->user()->approvalRequest;
                    if ($approvalRequest = $approvalRequest->where('approval_id', $approval->id)->last()) {
                        $approval->is_send_request = true;
                        // $approval->request_detail_is_send = [
                        //     'request_status' => $approvalRequest,
                        //     'require_detail' => $approvalRequest->requireDetail->groupBy('requirement_criteria_id')
                        // ];
                        $approval->request_detail_is_send = clone $approvalRequest;
                        $approval->request_detail_is_send['require_detail'] = $approvalRequest->requireDetail->groupBy('requirement_criteria_id');
                    }
                }
                $approval = $approval->toArray();
                try {
                    $approval['request_detail_is_send'] = $approval['request_detail_is_send']->toArray();
                    unset($approval['request_detail_is_send']['request_sender']);
                    foreach ($approval['request_detail_is_send']['require_detail'] as $key => $approvalValue) {
                    
                        $qualified = true;
                        foreach ($approvalValue as $index => $value) {
                            $qualified = $qualified && $value['qualified'];
                        }
                        $approvalRequire = [
                            'qualified' => $qualified,
                            'files' => $approvalValue
                        ];
        
                        $approval['request_detail_is_send']['require_detail'][$key] = $approvalRequire;
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
        } else {
            $approval = [
                'status' => 'notopen',
                'status_description' => 'Chưa mở',
                'is_send_request' => false,
                'user' => auth()->check() ? auth()->user()->toArray() : null,
                'request_detail_is_send' => null,
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
            $approval = Approval::happenning()->first();
            foreach ($files as $key => $file) {

                $require = str()->of($key)->explode('_');
                $requireId = $require[0] . '_' . $require[1];            
                $uploadPath = '/uploads/requests/' . $approval->id . '/' . auth()->user()['slug'];

                $fileName = Str::replaceLast('.' . $file->extension(), '-' . rand(1111, 9999) . '.' . $file->extension(), date('dmY-His') . '_' . $file->getClientOriginalName());

                if ($file->move(public_path($uploadPath), $fileName)) {
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