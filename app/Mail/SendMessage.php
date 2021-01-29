<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Message instance
     *
     * @var  \App\Models\Message
     */
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->withSwiftMessage(function ($message) {
            $message->messageDetails = $this->message;
        });

        $mailable = $this->from(
                $this->message->sender_email ?: $this->message->owner->email
            )
            ->text("messages.email_text")
            ->view("messages.email")
            ->with([
                "msg"   => $this->message
            ]);

        $this->message->attachments->each(function ($attachment) use (&$mailable) {
            $mailable->attach($attachment->file_path);
        });

        return $mailable;
    }
}
