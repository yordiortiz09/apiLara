<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class sms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;




    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Http::post('https://rest.nexmo.com/sms/json', [
            "from" => "Vonage APIs",
            'api_key' => "1edbb5f0",
            'api_secret' => "8su9DuBrJVSLewvT",
            'to' => "52{$this->user->phone}",
            'text' => "Tu codigo de verificacion es: {$this->user->verification_code}, sigue las instrucciones en tu correo electronico",
        ]);
    }
}
