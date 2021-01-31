<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class StatsAPITest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_fetch_stats()
    {
        $user = User::factory()->create();

        Event::fake();
        
        Message::factory(2)->create([
            'user_id'   => $user->id,
            'status'    => 'pending'
        ]);

        Message::factory(3)->create([
            'user_id'   => $user->id,
            'status'    => 'sent'
        ]);

        Message::factory(4)->create([
            'user_id'   => $user->id,
            'status'    => 'failed'
        ]);

        $response = $this->actingAs($user)
            ->get("/api/stats?userId={$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                "code",
                "message",
                "data"  => [
                    "total",
                    "pending",
                    "failed",
                    "sent",
                    "updatedAt"
                ]
            ])
            ->assertJsonFragment([
                "total"     => 9,
                "pending"   => 2,
                "failed"    => 4,
                "sent"      => 3
            ]);
    }
}
