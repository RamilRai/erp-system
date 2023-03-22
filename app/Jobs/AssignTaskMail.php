<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendAssignTaskMail;
use Mail;

class AssignTaskMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $member;
    protected $taskManagement;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($member, $taskManagement)
    {
        $this->member = $member;
        $this->taskManagement = $taskManagement;
    }
    
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dd($this->taskManagement);
        $email = new SendAssignTaskMail($this->member, $this->taskManagement);
        Mail::to($this->member->email)->send($email);
    }
}
