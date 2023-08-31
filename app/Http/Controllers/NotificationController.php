<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ForumService;
use App\Helpers\Base64;
use App\Helpers\DateHelper;

class NotificationController extends Controller
{
    protected $forumService;

    public function __construct(ForumService $forumService) {
        $this->forumService = $forumService;
    }

    public function getList(Request $request) {

        try {
            $paginate = $request->input('paginate') ? $request->input('paginate') : 5;
            $results = $this->forumService->get([
                'paginate' => $paginate,
                'user_id' => $request->input('user_id'),
                'type' => 'notification',
                'order' => $request->input('order'),
                'active' => $request->input('active')
            ]);
            return response($results);

        } catch (\Throwable $th) {
            //throw $th;
            return \response([
                'error' => true,
                'msg' => 'Params error',
                'msg_vi' => 'Có thể lỗi do sai dữ liệu tham số truyền vào'
            ], 500);

        }

    }

    public function getBySlug(Request $request) {

        try {

            $result = $this->forumService->getBySlug(
                $request->input('slug')
            );
            return response($result);

        } catch (\Throwable $th) {
            return \response([
                'error' => true,
                'msg' => 'Params error',
                'msg_vi' => 'Có thể lỗi do sai dữ liệu tham số truyền vào'
            ], 500);
        }
    }
}