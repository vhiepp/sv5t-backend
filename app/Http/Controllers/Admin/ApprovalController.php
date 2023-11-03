<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Approval;
use App\Models\ApprovalRequest;
use App\Models\ApprovalRequestFileCriteria;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $results = Approval::latest()->get();
            // foreach ($results as $index => $result) {
            //     $results[$index]['date_start'] = date('d/m/Y', strtotime($result['date_start']));
            //     $results[$index]['date_end'] = date('d/m/Y', strtotime($result['date_end']));
            // }
            return response([
                'status' => 'success',
                'data' => $results
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => 'error',
                'error' => $th
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Approval::create([
                'date_start' => date('Y-m-d', strtotime($request->input('dateStart'))),
                'date_end' => date('Y-m-d', strtotime($request->input('dateEnd'))),
                'user_id' => auth()->user()['id']
            ]);

            return response([
                'staus' => 'success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => 'error',
                'error' => $th
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getApprovalRequest(Request $request) {
        $approvalId = $request->input('approval_id') ? $request->input('approval_id') : Approval::lastest()->id;

        $order = 'await_approved';
        switch ($request->input('order')) {
            case 'approved':
                $order = 'approved';
                break;
            case 'not_approved':
                $order = 'not_approved';
                break;
            default:
                $order = 'await_approved';
                break;
        }
        $approvalRequest = ApprovalRequest::where('approval_id', $approvalId)->orderBy('created_at', 'desc')->where('status', $order);
        $paginate = $request->input('paginate') ? $request->input('paginate') : 8;
        $approvalRequest = $approvalRequest->paginate($paginate);
        foreach ($approvalRequest as $index => $aq) {
            $aq['require_detail_is_send'] = $aq->requireDetail->groupBy('requirement_criteria_id');
        }
        return response()->json($approvalRequest);
    }

    public static function commentRequestFile(Request $request) {
        $approvalRequestFileId = $request->input('file_id');

        ApprovalRequestFileCriteria::where('id', $approvalRequestFileId)->update([
            'comment' => $request->input('comment')
        ]);

        return response()->json([
            'status' => 'success',
            'error' => false
        ]);
    }

    public function qualifiedApprovalRequestFile(Request $request) {
        
        try {
            $qualified = ApprovalRequestFileCriteria::where('id', $request->input('file_id'))->first()->toArray()['qualified'];
        
            ApprovalRequestFileCriteria::where('id', $request->input('file_id'))
                                        ->update([
                                            'qualified' => !$qualified
                                        ]);
            return response()->json([
                'status' => 'success',
                'error' => false
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'error' => true
            ]);
        }
        
    }

    public function qualifiedApprovalRequestFileAllRequire(Request $request) {
        
        // try {
        //     $qualified = ApprovalRequestFileCriteria::where('id', $request->input('file_id'))->first()->toArray()['qualified'];
        
        //     ApprovalRequestFileCriteria::where('id', $request->input('file_id'))
        //                                 ->update([
        //                                     'qualified' => !$qualified
        //                                 ]);
        //     return response()->json([
        //         'status' => 'success',
        //         'error' => false
        //     ]);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' => 'error',
        //         'error' => true
        //     ]);
        // }
        
    }
}
