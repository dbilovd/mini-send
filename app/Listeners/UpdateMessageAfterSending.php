<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateMessageAfterSending
{
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
        $message = $event->message->messageDetails;
        if (!$message) {
            Log::debug("Message details not found.", compact('message'));
            return;
        }

        Log::debug("Message Sent: {$message->id}");


        $message->status = "sent";
        $message->sent_at = now();
        $message->save();
        Log::debug("Message status updated to sent!", compact('message'));
    }
}
