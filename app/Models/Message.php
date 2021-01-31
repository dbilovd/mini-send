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
     * Scope: Filter by search
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  String                                 $search 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilteredBySearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($qry) use ($search) {
            return $qry->where("subject", "LIKE", "{$search}%")
                /*
                 * @disabled as it is not part of the requirements.
                 * 
                    ->orWhere("body_text", "LIKE", "%{$search}%")
                    ->orWhere("body_html", "LIKE", "%{$search}%")
                */
                ;
        });
    }

    /**
     * Scope: Filter by status
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  String                                 $email
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilteredByStatus($query, $status)
    {
        if (!$status) {
            return $query;
        }

        $status = in_array($status, ["pending", "sent", "failed"]) ? $status : "pending";
        return $query->where("status", $status);
    }

    /**
     * Scope: Filter by Recipient email
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  String                                 $email
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilteredByRecipientsEmail($query, $email)
    {
        if (!$email) {
            return $query;
        }
        
        return $query->where("recipient_email", $email);
    }

    /**
     * Scope: Filter by sender's email
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  String                                 $email
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilteredBySendersEmail($query, $email)
    {
        if (!$email) {
            return $query;
        }
        
        return $query->where("sender_email", $email);
    }

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
