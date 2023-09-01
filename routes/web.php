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
    // $response = Http::withHeaders([
    //     'Content-Type' => 'application/json',
    // ])->post('https://ttsv.tvu.edu.vn/api/sch/w-locdskhoasinhviencotkb', [
    //     'filter' => [
    //         'hoc_ky' => 20232
    //     ],
    // ])->json();

    // if ($response['result'] && $response['code'] == 200) {

    //     foreach ($response['data']['ds_du_lieu'] as $data) {
    //         Unit::create([
    //             'name' => $data['ten_du_lieu']
    //         ]);
    //     }
    // }
    return response(Unit::all());
});
