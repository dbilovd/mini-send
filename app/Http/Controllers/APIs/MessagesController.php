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

			$messagesQuery = $user->messages();

			if ($request->has("search") && $request->get("search") != "") {
				$searchTerm = $request->get("search");
				$messagesQuery = $messagesQuery->where(function ($query) use ($searchTerm) {
					return $query->where("subject", "LIKE", "{$searchTerm}%")
						/*
						 * @disabled as it is not part of the requirements.
						 * 
							->orWhere("body_text", "LIKE", "%{$searchTerm}%")
							->orWhere("body_html", "LIKE", "%{$searchTerm}%")
						*/
						;
				});
			}

			if ($request->has("recipientEmail") && $request->get("recipientEmail") != "") {
				$recipientEmail = $request->get("recipientEmail");
				$messagesQuery = $messagesQuery->where("recipient_email", $recipientEmail);
			}

			if ($request->has("senderEmail") && $request->get("senderEmail") != "") {
				$senderEmail = $request->get("senderEmail");
				$messagesQuery = $messagesQuery->where("sender_email", $senderEmail);
			}

			$messages = $messagesQuery->paginate(
				$request->get("perPage") ?: 50
			);

			$messagesForResponse = $messages->map(function ($message) {
				return $message->formatForApi();
			});

			return response()->json([
				'code'		=> 200,
				'message'	=> 'Fetched messages',
				'data' 		=> $messagesForResponse,
	            'meta'		=> [
	                'links' => [
	                    'first' => $messages->url(1),
	                    'last' => $messages->url($messages->lastPage()),
	                    'prev' => $messages->previousPageUrl(),
	                    'next' => $messages->nextPageUrl(),
	                ],
	                'meta' =>
	                [
	                    'current_page' => $messages->currentPage(),
	                    'from' => $messages->firstItem(),
	                    'last_page' => $messages->lastPage(),
	                    'path' => $messages->resolveCurrentPath(),
	                    'per_page' => $messages->perPage(),
	                    'to' => $messages->lastItem(),
	                    'total' => $messages->total(),
	                ],
	            ]
			]);
		} catch (Exception $e) {
			Log::debug("An error occurred while fetching messages: {$e->getMessage()}", compact('e'));
			return response()->json([
				"code"		=> 500,
				"mesage"	=> "An error occurred while fetching messages. Please try again later."
			], 500);
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

			return response()->json([
				'code'		=> 200,
				'message'	=> 'Fetched messages',
				'data' 		=> $message->formatForApi()
			]);
		} catch (Exception $e) {
			Log::debug("An error occurred while fetching messages: {$e->getMessage()}", compact('e'));
			return response()->json([
				"code"		=> 500,
				"mesage"	=> "An error occurred while fetching messages. Please try again later."
			], 500);
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

			return response()->json([
				"code"		=> 201,
				"message"	=> "Placed message for sending",
				"data"		=> $message->formatForApi()
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
