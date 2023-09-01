<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getProfile(Request $request) {
        $user = null;
        try {
            if ($request->input('id')) {
                $user = User::where('id', $request->input('id'))->first();

            } else {
                $user = User::where('slug', $request->input('slug'))->first();
            }
        } catch (\Throwable $th) {
            return response([
                'error' => true,
                'user' => null
            ]);
        }
        if ($user) {
            return response([
                'error' => false,
                'user' => $user
            ]);
        }
        return response([
            'error' => true,
            'user' => null
        ]);
    }
}