<?php

namespace Tests\Feature;

use App\Events\MessageCreated;
use App\Models\Attachment;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
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

    /** @test */
    public function it_can_attach_attachments_when_creating_messages_to_send()
    {
        Storage::fake("attachments");

        $user = User::factory()->create();
        $message = Message::factory()->make([
            'user_id'   => $user->id
        ]);
        $attachments = Attachment::factory(2)->create();

        $response = $this->actingAs($user)
            ->post("/api/messages", [
                "userId"            => $user->id,
                "senderEmail"       => $message->sender_email,
                "recipientEmail"    => $message->recipient_email,
                "subject"           => $message->subject,
                "bodyAsText"        => $message->body_text,
                "bodyAsHtml"        => $message->body_html,
                "attachments"       => $attachments->pluck("id")->toArray()
            ]);

        $response->assertStatus(201);
        $attachments->each(function ($attachment) use ($response) {
            $response->assertJsonFragment([
                "attachmentId"  => $attachment->id,
                "filePath"      => $attachment->file_path,
                "createdAt"     => $attachment->created_at
            ]);
        });       
    }

    /** @test */
    public function it_can_return_a_paginated_list_of_all_messages_sent()
    {
        $user = User::factory()->create();
        $messages = Message::factory(5)->create([
            'user_id'   => $user->id
        ]);

        $response = $this->get("/api/messages?userId={$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                "code",
                "message",
                "data",
                "meta"
            ]);

        $message = $messages->random();
        $response->assertJsonFragment([
            "messageId"         => $message->id,
            "userId"            => (string) $message->user_id,
            "senderEmail"       => $message->sender_email,
            "recipientEmail"    => $message->recipient_email,
            "subject"           => $message->subject,
            "bodyAsText"        => trim($message->body_text),
            "bodyAsHtml"        => trim($message->body_html),
        ]);
    }
}
