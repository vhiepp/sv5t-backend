<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RequirementCriteria;
use App\Models\User;
use App\Models\Forum;
use App\Models\Comment;
use App\Models\Heart;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UnitSeeder::class,
            ClassInfoSeeder::class,
            RequirementCreteriaSeeder::class,
            UserSeeder::class,
            ForumSeeder::class
        ]);
    }
}