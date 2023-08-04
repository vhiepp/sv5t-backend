<?php

use Illuminate\Support\Facades\Route;
use App\Models\Forum;
use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\DateHelper;
use App\Models\Approval;
use App\Models\Unit;
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
  $result = Forum::find(1);

  return response()->json($result->toArray());
});