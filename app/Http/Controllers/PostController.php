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

            foreach ($results['data'] as $index => $result) {
                $results['data'][$index]['creator'] = [

                    'user_id' => Base64::id_encode($result['user']['id']),
                    'fullname' => $result['user']['fullname'],
                    'sur_name' => $result['user']['sur_name'],
                    'given_name' => $result['user']['given_name'],
                    'email' => $result['user']['email'],
                    'class' => $result['user']['class'],
                    'stu_code' => $result['user']['stu_code'],
                    'role' => $result['user']['role'],
                    'avatar' => $result['user']['avatar'],

                ];

                unset($results['data'][$index]['user']);
                unset($results['data'][$index]['user_id']);

                $results['data'][$index]['created_time'] = DateHelper::make($result['created_time']);
                $results['data'][$index]['updated_time'] = DateHelper::make($result['updated_time']);
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
        $result = $this->forumService->getBySlug(
            $request->input('slug')
        );

        $result['creator'] = [

            'user_id' => Base64::id_encode($result['user']['id']),
            'fullname' => $result['user']['fullname'],
            'sur_name' => $result['user']['sur_name'],
            'given_name' => $result['user']['given_name'],
            'email' => $result['user']['email'],
            'class' => $result['user']['class'],
            'stu_code' => $result['user']['stu_code'],
            'role' => $result['user']['role'],
            'avatar' => $result['user']['avatar'],

        ];

        unset($result['user']);
        unset($result['user_id']);

        $result['created_time'] = DateHelper::make($result['created_time']);
        $result['updated_time'] = DateHelper::make($result['updated_time']);

        return response($result);
        try {

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
            //code...
            $post = $this->forumService->create($request, 'post');
            return response([
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
    }
}