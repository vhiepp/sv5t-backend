<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Approval;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $results = Approval::latest()->get();
            // foreach ($results as $index => $result) {
            //     $results[$index]['date_start'] = date('d/m/Y', strtotime($result['date_start']));
            //     $results[$index]['date_end'] = date('d/m/Y', strtotime($result['date_end']));
            // }
            return response([
                'status' => 'success',
                'data' => $results
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => 'error',
                'error' => $th
            ], 500);
        }
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
            Approval::create([
                'date_start' => date('Y-m-d', strtotime($request->input('dateStart'))),
                'date_end' => date('Y-m-d', strtotime($request->input('dateEnd'))),
                'user_id' => auth()->user()['id']
            ]);

            return response([
                'staus' => 'success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'status' => 'error',
                'error' => $th
            ], 500);
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
    public function destroy(string $id)
    {
        //
    }
}
