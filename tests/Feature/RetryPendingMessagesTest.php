<?php

namespace Tests\Feature;

use App\Events\MessageReadyForResending;
use App\Jobs\RetrySendingPendingMessages;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RetryPendingMessagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_retrys_sending_a_message_that_has_been_pending_for_more_than_10_minutes()
    {
        Event::fake();

        $message = Message::factory()->create([
            'status'        => 'pending',
            'updated_at'    => now()->subMinutes(11)
        ]);

        (new RetrySendingPendingMessages())->handle();

        Event::assertDispatched(function (MessageReadyForResending $event) use ($message) {
            return $event->message->id == $message->id;
        });
    }
}
