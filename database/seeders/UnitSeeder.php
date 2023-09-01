<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;
use Illuminate\Support\Facades\Http;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://ttsv.tvu.edu.vn/api/sch/w-locdskhoasinhviencotkb', [
            'filter' => [
                'hoc_ky' => 20232
            ],
        ])->json();

        if ($response['result'] && $response['code'] == 200) {
            foreach ($response['data']['ds_du_lieu'] as $data) {
                if ($data['ten_du_lieu'] != 'Không đơn vị') {
                    Unit::create([
                        'name' => $data['ten_du_lieu']
                    ]);
                }
            }
        }
    }
}