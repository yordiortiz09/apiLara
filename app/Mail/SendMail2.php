<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail2 extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;
    protected $url;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user,$url)
    {
        $this->user= $user;
        $this->url= $url;   
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '2 do Paso a seguir',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.bienvenido',
            with: [
                "name"=>$this->user->name,
                "gmail"=>$this->user->email,
                
                "url"=>$this->url,

            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
