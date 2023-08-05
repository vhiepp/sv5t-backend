<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\Heart;

class HeartController extends Controller
{
    public function heart(Request $request) {
        $isHeart = false; $count = 0;
        if ($request->input('slug')) {
            $result = Forum::where('active', 1)
                        ->where('slug', $request->input('slug'))
                        ->select('id')->first();
            $heart = Heart::where('user_id', auth()->user()['id'])
                        ->where('forum_id', $result['id'])
                        ->first();
            if ($heart) {
                if ($heart['active'] == 1) {
                    Heart::where('user_id', auth()->user()['id'])
                        ->where('forum_id', $result['id'])
                        ->update([
                            'active' => 0
                        ]);
                    $isHeart = false;
                } else {
                    Heart::where('user_id', auth()->user()['id'])
                    ->where('forum_id', $result['id'])
                    ->update([
                        'active' => 1
                    ]);
                    $isHeart = true;
                }
            } else {
                Heart::create([
                    'user_id' => auth()->user()['id'],
                    'forum_id' => $result['id']
                ]);
                $isHeart = true;
            }

            $count = Heart::where('forum_id', $result['id'])
                        ->where('active', 1)->count();
        }
        return [
            'is_heart' => $isHeart,
            'count' => $count
        ];
    }
}