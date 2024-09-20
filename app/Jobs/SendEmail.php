<?php

namespace App\Jobs;

use App\Mail\DynamicEmail;
use App\Models\EmailFrame;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user, $content, $body;
    /**
     * Create a new job instance.
     */
    public function __construct(EmailFrame $content, User $user, $body)
    {
        $this->content= $content;
        $this->user = $user;
        $this->body = $body;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Mail::to($this->user->email)->send(new DynamicEmail($this->content,$this->user,$this->body));
    }
}
