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

    public function destroy(Request $request)
    {
        try {
            //code...
            $status = Comment::where('id', $request->input('id'))->where('user_id', auth()->user()['id'])->deleteActive();
            if ($status) {
                return response([
                    'status' => 'success',
                    'error' => false,
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response([
            'status' => 'error',
            'error' => true,
        ]);
    }
}
