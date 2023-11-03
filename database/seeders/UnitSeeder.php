<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;
use Illuminate\Support\Facades\Http;
use File;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = public_path('/data/unit_info.json');
        $content = File::get($filePath);
        echo "Get unit info data...\n";

        if (Str::isJson($content)) {
            $results = json_decode($content);

            foreach ($results as $unit) {

                Unit::firstOrCreate([
                    'name' => $unit->name,
                ]);

            }   
        }

        echo "Update unit info data success...\n";
    }
}