<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\DateHelper;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {


  dd(DateHelper::make('2023-06-21 03:14:22'));

});
