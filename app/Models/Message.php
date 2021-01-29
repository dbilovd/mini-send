<?php

namespace App\Models;

use App\Events\MessageCreated;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		"user_id",
		"sender_email",
		"recipient_email",
		"subject",
		"body_text",
		"body_html",
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => MessageCreated::class,
    ];

    /**
     * Relationship: Attachment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attachments()
    {
        return $this->belongsToMany(
            Attachment::class,
            "attachment_message",
            "message_id",
            "attachment_id"
        );
    }
}
