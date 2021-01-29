<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessagesController extends Controller
{
	/**
	 * Create a new Message.
	 * This method begins the process of sending a message.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(Request $request)
	{
		$data = $request->validate([
			"userId"			=> "required|exists:users,id",
			"senderEmail"       => "nullable|email",
            "recipientEmail"    => "required|email",
            "subject"           => "required",
            "bodyAsText"        => "nullable",
            "bodyAsHtml"		=> "nullable",
            "attachments"		=> "nullable|array",
            "attachments.*"		=> "required|exists:attachments,id",
		], []);

		try {
			// $user = $request->user();
			$user = User::find($data["userId"]);

			$message = $user->messages()->create([
				"sender_email" 		=> trim($data["senderEmail"]),
				"recipient_email" 	=> trim($data["recipientEmail"]),
				"subject" 			=> trim($data["subject"]),
				"body_text" 		=> trim($data["bodyAsText"]),
				"body_html" 		=> trim($data["bodyAsHtml"]),
			]);

			if (array_key_exists('attachments', $data) && !empty($data['attachments'])) {
				$message->attachments()->attach($data['attachments']);
			}

			return response()->json([
				"code"		=> 201,
				"message"	=> "Placed message for sending",
				"data"	=> [
					"messageId"			=> $message->id,
					"userId"			=> $message->user_id,
	                "senderEmail"       => $message->sender_email,
	                "recipientEmail"    => $message->recipient_email,
	                "subject"           => $message->subject,
	                "bodyAsText"        => $message->body_text,
	                "bodyAsHtml"        => $message->body_html,
                    "status"			=> $message->status,
                    "createdAt"			=> $message->created_at,
                    "updatedAt"			=> $message->updated_at,
                    "attachments"		=> $message->attachments->map(function ($attachment) {
                    	return [
                    		"attachmentId"	=> $attachment->id,
                    		"filePath"		=> $attachment->file_path,
                    		"createdAt"		=> $attachment->created_at
                    	];
                    })
				]
			], 201);
		} catch (Exception $e) {
			Log::debug("An error occurred while trying to add message: {$e->getMessage()}", compact('e'));
			return response()->json([
				"code"		=> 500,
				"mesage"	=> "An error occurred while sending message. Please try again later."
			], 500);
		}
	}
}
