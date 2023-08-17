<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'fullname' => 'Dương Văn Hiệp',
            'sur_name' => 'Dương',
            'given_name' => 'Văn Hiệp',
            'email' => 'vanhiep@admin.com',
            'role' => 'admin',
            'stu_code' => '110121209',
            'avatar' => env('APP_URL', 'http://localhost:8000') . '/assets/images/avatars/avatar_13.jpg',
            'password' => 'Hiep33@@'
        ]);
        User::factory(50)->create();
    }
}