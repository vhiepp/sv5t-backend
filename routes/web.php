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

    // $crawler = Goutte::request('GET', 'https://daotao.tvu.edu.vn/cvht?slNamhoc=2022-2023');
    // $classList = $crawler->filter('select[name=slMalop]')->each(function ($node) {
    //     return $node->children()->each(function ($class) {
    //         return $class->text();
    //     });
    // })[0];
    // unset($classList[0]);

    // return response($classList);

    // $response = Http::withHeaders([
    //     'Content-Type' => 'application/json',
    // ])->post('https://ttsv.tvu.edu.vn/api/sch/w-locdslopcotkb', [
    //     'filter' => [
    //         'hoc_ky' => 20232
    //     ],
    // ]);

    // $response = Http::withHeaders([
    //     'Content-Type' => 'application/x-www-form-urlencoded',
    // ])->asForm()->post('https://ttsv.tvu.edu.vn/api/auth/login', [
    //     'username' => '110121209',
    //     'password' => 'Hiep33@@',
    //     'grant_type' => 'password'
    // ]);

    // dd($response->json());


    // $user = User::where('slug', 'duongvanhiep')->first();
    // $user->classInfo;

    return response(['msg' => 'Not suported']);
});