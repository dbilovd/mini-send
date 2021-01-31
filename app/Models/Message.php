<?php

namespace App\Models;

use App\Events\MessageCreated;
use App\Models\Attachment;
use App\Models\User;
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

    /**
     * Relationship to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    /**
     * Format Response for API
     *
     * @return array
     */
    public function formatForApi()
    {
        return [
            "messageId"         => $this->id,
            "userId"            => $this->user_id,
            "senderEmail"       => $this->sender_email,
            "recipientEmail"    => $this->recipient_email,
            "subject"           => $this->subject,
            "bodyAsText"        => trim($this->body_text),
            "bodyAsHtml"        => trim($this->body_html),
            "status"            => $this->status,
            "createdAt"         => $this->created_at,
            "updatedAt"         => $this->updated_at,
            "attachments"       => $this->attachments->map(function ($attachment) {
                return $attachment->formatForApi();
            })
        ];
    }
}
