<?php

namespace App\Jobs;

use App\Events\MessageReadyForResending;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RetrySendingPendingMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $messages = Message::filteredByStatus("pending")
            ->where("updated_at", "<=", now()->subMinutes(10))
            ->latest()
            ->limit(1000);

        $messages->each(function ($message) {
            event(
                new MessageReadyForResending($message)
            );
        });
    }
}
