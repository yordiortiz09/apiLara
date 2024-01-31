<?php

namespace App\Jobs;

use App\Mail\SendMail2;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class email2 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected  $user;
    protected $name;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user,$email)
    {
        $this->user = $user;
        $this->name=$email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new SendMail2($this->user, $this->name));
    }
}
