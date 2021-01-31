<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessagesController extends Controller
{
	/**
	 * Return a paginated list of all messages sent by the current user
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request)
	{
		try{
			$userId = request()->get("userId");
			$user = User::findOrFail($userId);

			$messages = $user->messages()
				->filteredByStatus(
					$request->get("status")
				)
				->filteredByRecipientsEmail(
					$request->get("recipientEmail")
				)
				->filteredBySendersEmail(
					$request->get("senderEmail")
				)
				->filteredBySearch(
					$request->get("search")
				)
				->paginate(
					$request->get("perPage") ?: 50
				);

			return $this->paginatedSuccessResponse($messages);
		} catch (Exception $e) {
			Log::debug("An error occurred while fetching messages: {$e->getMessage()}", compact('e'));
			return $this->errorResponse(500, "An error occurred while fetching messages. Please try again later.");
		}
	}

	/**
	 * Return the details of a single message sent by the current user
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show(Request $request, $messageId)
	{
		try{
			$userId = request()->get("userId");
			$user = User::findOrFail($userId);

			$message = $user->messages()->findOrFail($messageId);

			return $this->successResponse(
				$message->formatForApi(),
				null,
				"Fetched messages"
			);
		} catch (Exception $e) {
			Log::debug("An error occurred while fetching messages: {$e->getMessage()}", compact('e'));
			return $this->errorResponse(500, "An error occurred while fetching messages. Please try again later.");
		}
		
	}

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

			return $this->successResponse($message->formatForApi(), 201, "Placed message for sending");
		} catch (Exception $e) {
			Log::debug("An error occurred while trying to add message: {$e->getMessage()}", compact('e'));
			return $this->errorResponse(500, "An error occurred while sending message. Please try again later.");
		}
	}
}
