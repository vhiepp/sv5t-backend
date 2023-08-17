<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function get() {
        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }
}