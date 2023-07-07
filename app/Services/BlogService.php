<?php

namespace App\Services;

use App\Models\Post;
use App\Helpers\Base64;

class BlogService {


    public static function getList($paginate = 5, $userId = null, $order = 'new') {

        $results = Post::where('active', 1)
                        ->where('type', 'blogs');
        
        if ($userId) {
            $id = Base64::id_decode($userId);
            $results = $results->where('user_id', $id);
        }

        $od = "desc";
        if ($order) {

            switch ($order) {
                case 'new':
                    $od = "desc";
                    break;
                case 'old':
                    $od = "asc";
                    break;
                default:
                    $od = "desc";
                    break;
            }

        }
        $results = $results->orderBy('created_at', $od);

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
                        ->where('type', 'blogs')
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