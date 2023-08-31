<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ForumService;
use App\Helpers\Base64;
use App\Helpers\DateHelper;

class PostController extends Controller
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
                'type' => 'post',
                'order' => $request->input('order'),
                'active' => $request->input('active')
            ]);

            return response($results);

        } catch (\Throwable $th) {
            return \response([
                'error' => true,
                'msg' => 'Params error',
                'msg_vi' => 'Có thể lỗi do sai dữ liệu tham số truyền vào'
            ], 500);
        }

    }

    public function getListForMe(Request $request) {

        try {

            $paginate = $request->input('paginate') ? $request->input('paginate') : 5;

            $results = $this->forumService->get([
                'paginate' => $paginate,
                'user_id' => auth()->user()['id'],
                'type' => 'post',
                'order' => $request->input('order'),
                'active' => $request->input('active') ? $request->input('active') : 'all'
            ]);

            foreach ($results['data'] as $index => $result) {
                $ac = 'active';
                switch ($results['data'][$index]['active']) {
                    case 1:
                        $ac = 'active';
                        break;
                    case 0:
                        $ac = 'wait';
                        break;
                    case -1:
                        $ac = 'deleted';
                        break;

                    default:
                        $ac = 'active';
                        break;
                }

                $results['data'][$index]['active'] = $ac;
            }

            return response($results);
        } catch (\Throwable $th) {
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $post = $this->forumService->create($request, 'post');

            return response([
                'post' => $post,
                'status' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response([
                'status' => 'error'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            //code...
            $status = Forum::where('type', 'post')->where('slug', $request->input('slug'))->where('user_id', auth()->user()['id'])->deleteActive();
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