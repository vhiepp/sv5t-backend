<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Câu lạc bộ Tin học'
            ],
            [
                'name' => 'Câu lạc bộ Sinh Viên 5 tốt'
            ],
            [
                'name' => 'Câu lạc bộ Thanh niên tình nguyện'
            ],
            [
                'name' => 'Câu lạc bộ Hành trình Sinh Viên'
            ],
            [
                'name' => 'Câu lạc bộ Người tốt việc tốt'
            ],
            [
                'name' => 'Câu lạc bộ Khởi Nghiệp'
            ],
        ];

        foreach ($data as $value) {
            Unit::create($value);
        }
    }
}
