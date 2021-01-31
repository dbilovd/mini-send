<?php

namespace App\Providers;

use App\Events\MessageCreated;
use App\Events\MessageReadyForResending;
use App\Listeners\ProcessNewMessage;
use App\Listeners\UpdateMessageAfterSending;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        MessageCreated::class => [
            ProcessNewMessage::class
        ],
        MessageSent::class => [
            UpdateMessageAfterSending::class
        ],
        MessageReadyForResending::class => [
            ProcessNewMessage::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
