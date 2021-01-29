<?php

namespace Tests\Feature;

use App\Listeners\UpdateMessageAfterSending;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\Events\MessageSent;
use Tests\TestCase;

class UpdateMessageAfterSendingListenerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_a_message_as_sent_if_message_was_sent_successfully()
    {
        $message = Message::factory()->create();

        $job = new MessageSent($message);

        (new UpdateMessageAfterSending())->handle($job);

        $message->refresh();

        $this->assertTrue($message->status === "sent");
    }
}
