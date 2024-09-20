<?php

namespace App\Mail;

use App\Models\EmailFrame;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DynamicEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $user, $content, $body;
    /**
     * Create a new message instance.
     */
    public function __construct(EmailFrame $content, User $user,$body)
    {
        $this->content = $content;
        $this->user = $user;
        $this->body = $body;

    }

//    public function envelope(): Envelope
//    {
//        return new Envelope(
//            subject: 'Đăng kí thành công',
//            tags: ['register'],
//        );
//    }
//
//    /**
//     * Get the message content definition.
//     */
//    public function content(): Content
//    {
//        return new Content(
//            view: 'mail.auth.registerMail',
//            with: [
//                'name' => $this->user->name,
//            ],
//        );
//    }
    public function build()
    {

        return $this
            ->subject($this->content->subject)
            ->view('mail.dynamic')
            ->with(['body' =>$this->body]);
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
