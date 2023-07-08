<?php

namespace App\Services;

use App\Models\Post;
use App\Helpers\Base64;

class PostService {

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

        $results = Post::where('active', $ac);

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
            'created_at as created_time',
            'updated_at as updated_time',
        )->paginate($paginate);

    }

    public function getBySlug($slug) {
        
        if ($slug) {
            $result = Post::where('active', 1)
                        ->where('slug', $slug);

            return $result
                    ->select(
                        'title',
                        'slug',
                        'content',
                        'thumb',
                        'active',
                        'type',
                        'user_id',
                        'created_at',
                        'updated_at',
                    )
                    ->get()[0];
        }
        return [];
    }

}