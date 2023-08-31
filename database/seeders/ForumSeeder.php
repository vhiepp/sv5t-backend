<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Forum;
use App\Models\Comment;
use App\Models\Heart;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = [];
        $users = User::select('id')->get()->toArray();
        foreach ($users as $index => $user) {
            array_push($userId, $user['id']);
        }

        for ($i=0; $i < 100; $i++) {
            Forum::create([
                'title' => fake()->unique()->sentence(12),
                'content' => fake()->realTextBetween($minNbChars = 1000, $maxNbChars = 10000, $indexSize = 2),
                'thumbnail' => fake()->imageUrl(360, 200, 'animals', true, 'cats'),
                'active' => fake()->randomElement([1, 1, 1, 1, 1, 1, 0, -1]),
                'type' => fake()->randomElement(['post', 'notification']),
                'description' => fake()->realTextBetween($minNbChars = 100, $maxNbChars = 300, $indexSize = 2),
                'user_id' => fake()->randomElement($userId),
                'created_at' => '2023-'. rand(1, 7) .'-'. rand(1, 30) .' '.rand(1, 20).':'. rand(1, 40) .':'. rand(1, 60)
            ]);
        }

        $forumId = [];
        $forums = Forum::select('id')->where('active', 1)->orWhere('active', -1)->get()->toArray();
        foreach ($forums as $index => $forum) {
            array_push($forumId, $forum['id']);
        }

        for ($i=0; $i < 1000; $i++) {
            Comment::create([
                'user_id' => fake()->randomElement($userId),
                'forum_id' => fake()->randomElement($forumId),
                'content' => fake()->realTextBetween($minNbChars = 10, $maxNbChars = 50, $indexSize = 2),
                'active' => 1
            ]);
        }

        for ($i=0; $i < 500; $i++) {
            Heart::create([
                'user_id' => fake()->randomElement($userId),
                'forum_id' => fake()->randomElement($forumId),
                'active' => 1
            ]);
        }
    }
}