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
        
        $results = $results->select(
            'id',
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
        
        $comments = []; $hearts = [];
        foreach ($results as $index => $forum) {
            $forum->user;
            array_push($comments, $forum->comments->where('active', 1));
            array_push($hearts, $forum->hearts->where('active', 1)->count());
        }
        $results = $results->toArray();
        foreach ($results['data'] as $index => $forum) {
            $comment = [
                'count' => count($comments[$index]),
                'data' => (auth()->check() && auth()->user()['role'] == 'admin') ? $comments[$index] : null
            ];
            $results['data'][$index]['comments'] = $comment;
            
            $results['data'][$index]['hearts'] = [
                'count' => $hearts[$index],
                'data' => null
            ];
        }

        return $results;
    }

    public function getBySlug($slug) {
        
        if ($slug) {
            $result = Forum::where('slug', $slug);

            $result = $result
                    ->select(
                        'id',
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
            $result->user;
            $comments = $result->comments->where('active', 1);
            $hearts = $result->hearts->where('active', 1);
            
            $result = $result->toArray();

            foreach ($comments as $index => $comment) {
                $comment->user;
            }
            
            $comments = $comments->toArray();
            $result['comments'] = [
                'count' => count($comments),
                'data' => array_splice($comments, 0, 5)
            ];
            $isMyHeart = false;
            if (auth()->check()) {
                foreach ($hearts as $index => $heart) {
                    if($heart['user_id'] == auth()->user()['id']) {
                        $isMyHeart = true;
                        break;
                    }
                }
            }
            $result['hearts'] = [
                'is_heart' => $isMyHeart,
                'count' => $hearts->count()
            ];

            return $result;
        }
        return [];
    }

}