<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
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
            if ($request->input('thumbnail')) {
                $path = env('APP_URL') . '/' . $request->input('thumbnail');
            } else {
                $folder = "uploads/" . date('Y/m/d');
                $fileName = date('H-i') . '-' . $request->file('thumbnail')->getClientOriginalName();
                $url = $request->file('thumbnail')->move($folder, $fileName);
                $path = env('APP_URL') . '/' . $folder . '/' . $fileName;
            }
            
            $content = Str::of($request->input('content'))->replace('"', "'");

            Post::create([
                'title' => $request->input('title'),
                'content' => $content,
                'thumb' => $path,
                'active' => $request->input('postNow') ? 1 : 0,
                'type' => 'notification',
                'user_id' => auth()->user()['id']
            ]);
            
            return response([
                'status' => 'success',
                'msg' => 'Tạo thông báo thành công',
            ]);

        } catch (\Throwable $th) {
            
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
}