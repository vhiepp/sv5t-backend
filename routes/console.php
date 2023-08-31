<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\ClassInfo;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('class:getdata', function () {

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post('https://ttsv.tvu.edu.vn/api/sch/w-locdslopcotkb', [
        'filter' => [
            'hoc_ky' => 20232
        ],
    ])->json();

    if ($response['result'] && $response['code'] == 200) {

        foreach ($response['data']['ds_du_lieu'] as $data) {
            ClassInfo::create([
                'code' => $data['ma_du_lieu'],
                'name' => $data['ten_du_lieu'],
            ]);
        }

        echo 'Get data successfully :)';

    } else {
        echo 'Data fetch failed :(';
    }

})->purpose('Get class data in ttsv.tvu.edu.vn');
