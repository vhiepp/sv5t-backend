<?php

use Illuminate\Support\Facades\Route;
use App\Models\Forum;
use App\Models\User;
use Illuminate\Support\Str;
use App\Helpers\DateHelper;
use App\Models\Approval;
use App\Models\Unit;
use Illuminate\Support\Facades\Http;
use App\Models\ClassInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;
use App\Jobs\SendEmail;
use Carbon\Carbon;

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

Route::get('/', function (Request $request) {

    $users = User::all();
    dd($users);
    // SendEmail::dispatch($users);
    // return   "Gui mail thanh cong";
    // try {
    //     //code...
    // } catch (\Throwable $th) {
    //     return "Gui mail that bai";
    // }

});
