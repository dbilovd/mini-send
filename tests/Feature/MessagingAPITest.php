<?php

namespace Tests\Feature;

use App\Events\MessageCreated;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MessagingAPITest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_message_to_be_sent()
    {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        $message = Message::factory()->make([
            'user_id'   => $user->id
        ]);

        $response = $this->actingAs($user)
            ->post("/api/messages", [
                "userId"            => $user->id,
                "senderEmail"       => $message->sender_email,
                "recipientEmail"    => $message->recipient_email,
                "subject"           => $message->subject,
                "bodyAsText"        => $message->body_text,
                "bodyAsHtml"        => $message->body_html,
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    "messageId",
                    "userId",
                    "senderEmail",
                    "recipientEmail",
                    "subject",
                    "bodyAsText",
                    "bodyAsHtml",
                    "status",
                    "createdAt",
                    "updatedAt"
                ]
            ])
            ->assertJsonFragment([
                "userId"            => $user->id,
                "senderEmail"       => $message->sender_email,
                "recipientEmail"    => $message->recipient_email,
                "subject"           => $message->subject,
                "bodyAsText"        => trim($message->body_text),
                "bodyAsHtml"        => trim($message->body_html),
            ]);

        $this->assertDatabaseHas("messages", [
            "user_id"           => $user->id,
            "sender_email"      => $message->sender_email,
            "recipient_email"   => $message->recipient_email,
            "subject"           => $message->subject,
            "body_text"         => trim($message->body_text),
            "body_html"         => trim($message->body_html),
        ]);
    }

   /** @test */
   public function it_fires_a_message_created_event_when_a_new_message_is_created()
   {
        Event::fake([
            MessageCreated::class
        ]);

        $user = User::factory()->create();
        $message = Message::factory()->make([
            'user_id'   => $user->id
        ]);

        $response = $this->actingAs($user)
            ->post("/api/messages", [
                "userId"            => $user->id,
                "senderEmail"       => $message->sender_email,
                "recipientEmail"    => $message->recipient_email,
                "subject"           => $message->subject,
                "bodyAsText"        => $message->body_text,
                "bodyAsHtml"        => $message->body_html,
            ]);

        $response->assertStatus(201);

        Event::assertDispatched(function (MessageCreated $job) use ($message) {
            return $job->message->sender_email == $message->sender_email
                && $job->message->recipient_email == $message->recipient_email
                && $job->message->subject == $message->subject;
        });
   }
}
