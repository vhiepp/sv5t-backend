<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Approval;
use App\Models\ApprovalRequest;
use App\Models\ApprovalRequestFileCriteria;
use App\Models\User;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $approval = Approval::create([
            'date_start' => now(),
            'date_end' => now()->addDay(60),
            'user_id' => User::where('email', 'vanhiep@admin.com')->first()['id']
        ]);

        $users = User::limit(rand(40, 70))->get();

        foreach ($users as $index => $user) {

            $approvalRequestStatus = fake()->randomElement(['await_approved', 'await_approved', 'not_approved', 'await_approved', 'approved']);
            $approvalRequest = ApprovalRequest::create([
                'user_id' => $user->id,
                'status' => $approvalRequestStatus,
            ]);

            $qualified = $approvalRequestStatus == 'approved';

            for ($i = 1; $i <= 5; $i++) {
                $n = rand(1, 2);
                for ($j = 1; $j <= $n; $j++) {
                    ApprovalRequestFileCriteria::create([
                        'file_name' => fake()->unique()->sentence(12) . 'pdf',
                        'file_url' => env('APP_URL', 'http://localhost:8000') . '/data/files/' . rand(1, 25) . '.pdf',
                        'approval_request_id' => $approvalRequest->id,
                        'requirement_criteria_id' => 'require_' . $i,
                        'qualified' => $qualified
                    ]);
                }
            }
        }
    }
}
