<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RequirementCriteria;

class RequirementCreteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirementCriteriaList = [
            'Đạo đức tốt',
            'Học tập tốt',
            'Thể lực tốt',
            'Tình nguyện tốt',
            'Hội nhập tốt'

        ];
        foreach ($requirementCriteriaList as $key => $value) {
            # code...
            RequirementCriteria::create([
                'name' => $value
            ]);
        }
    }
}