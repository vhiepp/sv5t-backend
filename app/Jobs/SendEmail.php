<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailNotify;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $users;

    /**
     * Create a new job instance.
     */
    public function __construct($users)
    {
        $this->users = $users;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $userList = [
        //     "dhiep9798@gmail.com",
        //     "110121209@st.tvu.edu.vn"
        // ];
        $userList = [];
        foreach ($this->users as $index => $user) {
            array_push($userList, $user->email);
            if (($index + 1) % 50 == 0) {
                Mail::to($userList)->send(new MailNotify(['year' => 2023]));
                $userList = [];
            }
        }

        Mail::to($userList)->send(new MailNotify(['year' => 2023]));
    }
}
