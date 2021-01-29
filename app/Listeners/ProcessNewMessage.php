<?php

namespace App\Listeners;

use App\Mail\SendMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ProcessNewMessage
{
    /**
     * Message instance
     *
     * @var  \App\Models\Message
     */
    public $message;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $message = $event->message;

        Mail::to($message->recipient_email)
            ->send(
                new SendMessage($message)
            );
    }
}
