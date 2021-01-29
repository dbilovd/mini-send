<?php

namespace Tests\Feature;

use App\Events\MessageCreated;
use App\Listeners\ProcessNewMessage;
use App\Mail\SendMessage;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProcessMessageListenerTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_attempts_to_send_a_mail_when_handle_is_called()
    {
        Mail::fake();

        $message = Message::factory()->create();
        $job = new MessageCreated($message);

        (new ProcessNewMessage())->handle($job);

        Mail::assertQueued(function (SendMessage $mail) use ($message) {
            return $mail->message->id === $message->id;
        });
    }
}
