<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\Comment;
use App\Helpers\DateHelper;

class CommentController extends Controller
{
    public function get(Request $request) {
        $comments = [
            'data' => [],
            'total' => 0
        ];
        if ($request->input('slug')) {
            $order = "desc";
            if ($request->input('order')) {
                switch ($request->input('order')) {
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
            $forum = Forum::where('slug', $request->input('slug'))->select('id')->first();
            $paginate = $request->input('paginate') ? $request->input('paginate') : 5;
            if ($forum) {
                $comments = Comment::where('active', 1)
                                    ->where('forum_id', $forum['id'])
                                    ->orderBy('created_at', $order)
                                    ->paginate($paginate);
                foreach ($comments as $index => $comment) {
                    $comments[$index]['user'] = $comment->user;
                    $comments[$index]['created_time'] = DateHelper::make($comments[$index]['created_at']);
                    $comments[$index]['updated_time'] = DateHelper::make($comments[$index]['updated_at']);
                    unset($comments[$index]['created_at']);
                    unset($comments[$index]['updated_at']);
                }
            }
        }
        return response()->json($comments);
    }

    public function create(Request $request) {
        try {
            $forum = Forum::where('slug', $request->input('slug'))->select('id')->first();
            $comment = Comment::create([
                'content' => $request->input('content'),
                'forum_id' => $forum['id'],
                'user_id' => auth()->user()['id']
            ]);
            $comment['user'] = $comment->user;

            $comment['created_time'] = DateHelper::make($comment['created_at']);
            $comment['updated_time'] = DateHelper::make($comment['updated_at']);
            unset($comment['created_at']);
            unset($comment['updated_at']);
            return response([
                'comment' => $comment,
                'status' => 'success',
                'error' => false
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'error' => true,
                'status' => 'error',
            ]);
        }
    }
}
