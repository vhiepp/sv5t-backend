<?php

namespace App\Services;

use App\Models\Forum;
use App\Helpers\Base64;
use App\Helpers\DateHelper;
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
            'thumbnail' => $path,
            'active' => auth()->user()['role'] == 'admin' && $request->input('postNow') ? 1 : 0,
            'type' => $type,
            'description' => $request->input('description'),
            'user_id' => auth()->user()['id']
        ]);
        if ($request->input('origin')) {
            $forum->origins()->create([
                'link' => $request->input('origin')
            ]);
        }
        $forum->origins;
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
            $data['thumbnail'] = $request->input('thumbnail');
        }
        $data['active'] = auth()->user()['role'] == 'admin' && $request->input('postNow') ? 1 : 0;
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
            case 'all':
                $ac = -2;
                break;
            default:
                $ac = 1;
                break;
        }

        if ($data['active'] != 'all') {
            $results = Forum::where('active', $ac);
        } else {
            $results = Forum::where('active', '<>', $ac);
        }

        if ($data['type']) {
            $results = $results->where('type', $data['type']);
        }

        if ($data['user_id']) {
            $results = $results->where('user_id', $data['user_id']);
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

        $results = $results->paginate($paginate);

        $comments = []; $hearts = [];
        foreach ($results as $index => $forum) {
            if (!auth()->check() || auth()->user()['role'] != 'admin') {
                unset($results[$index]['content']);
            }

            $forum->creator;
            $cms = $forum->comments->where('active', 1);
            $count = $cms->count();
            if (auth()->check() && auth()->user()['role'] == 'admin') {
                foreach ($cms as $index => $cm) {
                    $cm->user;
                    $cms[$index]['create_time'] = DateHelper::make($cm['created_at']);
                    if ($index > 4) break;
                }
                $cms = $cms->toArray();
                $cms = array_splice($cms, 0, 5);
            }
            array_push($comments, [
                'count' => $count,
                'data' => $cms
            ]);
            array_push($hearts, $forum->hearts->where('active', 1));
        }
        $results = $results->toArray();
        foreach ($results['data'] as $index => $forum) {
            $comment = [
                'count' => $comments[$index]['count'],
                'data' => (auth()->check() && auth()->user()['role'] == 'admin') ? $comments[$index]['data'] : null
            ];
            $results['data'][$index]['comments'] = $comment;

            $isMyHeart = false;
            if (auth()->check()) {
                $thisHearts = $hearts[$index]->where('user_id', auth()->user()['id']);
                if ($thisHearts->count() > 0) {
                    $isMyHeart = true;
                }
            }
            $results['data'][$index]['hearts'] = [
                'count' => $hearts[$index]->count(),
                'data' => null,
                'is_heart' => $isMyHeart
            ];
        }

        return $results;
    }

    public function getBySlug($slug) {

        if ($slug) {
            $result = Forum::where('slug', $slug);

            $result = $result->get()[0];
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