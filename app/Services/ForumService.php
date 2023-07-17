<?php

namespace App\Services;

use App\Models\Forum;
use App\Helpers\Base64;
use Illuminate\Support\Str;

class ForumService {

    public function create($request, $type = 'post')
    {

            if ($request->input('thumbnail')) {
                $path = env('APP_URL') . '/' . $request->input('thumbnail');
            } else {
                $folder = "uploads/" . date('Y/m/d');
                $fileName = date('H-i') . '-' . $request->file('thumbnail')->getClientOriginalName();
                $url = $request->file('thumbnail')->move($folder, $fileName);
                $path = env('APP_URL') . '/' . $folder . '/' . $fileName;
            }
            
            $content = Str::of($request->input('content'))->replace('"', "'");

            $forum = Forum::create([
                'title' => $request->input('title'),
                'content' => $content,
                'thumb' => $path,
                'active' => $request->input('postNow') ? 1 : 0,
                'type' => $type,
                'description' => $request->input('description'),
                'user_id' => auth()->user()['id']
            ]);

            return $forum;
    }

    public function update($request) {
        $data = [];
        if ($request->input('title')) {
            $data['title'] = $request->input('title');
        }
        if ($request->input('content')) {
            $content = Str::of($request->input('content'))->replace('"', "'");
            $data['content'] = $content;
        }
        if ($request->input('thumbnail')) {
            $data['thumb'] = $request->input('thumbnail');
        }
        $data['active'] = $request->input('postNow') ? 1 : 0;
        if ($request->input('description')) {
            $data['description'] = $request->input('description');
        }

        return Forum::where('slug', $request->input('slug'))
                    ->update($data);
    } 

    public function get($data) {

        $ac = 1;
        switch ($data['active']) {
            case 'active':
                $ac = 1;
                break;
            case 'wait':
                $ac = 0;
                break;
            case 'deleted':
                $ac = -1;
                break;
            
            default:
                $ac = 1;
                break;
        }

        $results = Forum::where('active', $ac);

        if ($data['type']) {
            $results = $results->where('type', $data['type']);
        }

        if ($data['user_id']) {
            $id = Base64::id_decode($data['user_id']);
            $results = $results->where('user_id', $id);
        }

        $order = "desc";
        if ($data['order']) {

            switch ($data['order']) {
                case 'new':
                    $order = "desc";
                    break;
                case 'old':
                    $order = "asc";
                    break;
                default:
                    $order = "desc";
                    break;
            }
            
        }
        $results = $results->orderBy('created_at', $order);

        $paginate = $data['paginate'] ? $data['paginate'] : 5;
        
        return $results->select(
            'title',
            'slug',
            'thumb as thumbnail',
            'active',
            'type',
            'user_id',
            'description',
            'content',
            'created_at as created_time',
            'updated_at as updated_time',
        )->paginate($paginate);

    }

    public function getBySlug($slug) {
        
        if ($slug) {
            $result = Forum::where('slug', $slug);

            return $result
                    ->select(
                        'title',
                        'slug',
                        'content',
                        'thumb',
                        'active',
                        'type',
                        'user_id',
                        'description',
                        'created_at as created_time',
                        'updated_at as updated_time',
                    )
                    ->get()[0];
        }
        return [];
    }

}