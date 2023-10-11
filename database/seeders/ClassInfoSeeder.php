<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ClassInfo;
use Illuminate\Support\Facades\Http;
use File;
use Illuminate\Support\Str;

class ClassInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = public_path('/data/class_info.json');
        $content = File::get($filePath);
        echo "Get class info data...\n";

        if (Str::isJson($content)) {
            $results = json_decode($content);

            foreach ($results as $classInfo) {

                ClassInfo::firstOrCreate([
                    'code' => $classInfo->ma_du_lieu,
                    'name' => $classInfo->ten_du_lieu,
                ]);

            }   
        }

        echo "Update class info data success...";
    }
}