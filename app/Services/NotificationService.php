<?php

namespace App\Services;

use App\Models\Post;
use App\Helpers\Base64;

class NotificationService {


    public static function getList($paginate = 5, $userId = null, $order = 'new', $active = 'active') {
        $ac = 1;
        switch ($active) {
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

        $results = Post::where('active', $ac)
                        ->where('type', 'notification');
        
        if ($userId) {
            $id = Base64::id_decode($userId);
            $results = $results->where('user_id', $id);
        }

        $od = "desc";
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
                        ->where('type', 'notification')
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