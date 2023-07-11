<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Forum;
use Illuminate\Support\Str;
use App\Services\ForumService;

class NotificationController extends Controller
{
    protected $forumService;

    public function __construct(ForumService $forumService) {
        $this->forumService = $forumService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            //code...
            $notification = $this->forumService->create($request, 'notification');
            return response([
                'status' => 'success',
            ]);
        } catch (\Throwable $th) {
            return response($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        
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
    public function update(Request $request)
    {
        try {
            //code...
            $post = $this->forumService->update($request);
            return response([
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function pendingCout() {
        $count = Forum::where('type', 'notification')
                    ->where('active', 0)
                    ->count();
        return response([
            'count' => $count,
        ]);
    }
}