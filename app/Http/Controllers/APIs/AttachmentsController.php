<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttachmentsController extends Controller
{
	/**
	 * Store a new attachment
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(Request $request)
	{
		try {
			$request->validate([
				"file"	=> "required|file"
			]);

			$file = $request->file("file");

			$filePath = $file->store("attachments");

			$attachment = Attachment::create([
				'original_file_name'	=> $filePath,
				'file_path'				=> $filePath
			]);

			return response()->json([
				"code"		=> 201,
				"message"	=> "Uploaded attachment successfully.",
				"data"		=> [
					"attachmentId"	=> $attachment->id,
					"path"			=> $attachment->file_path,
					"createdAt"		=> $attachment->created_at,
				]
			], 201);
		} catch(Exception $e) {
			$message = "An error occurred while uploading attachments: {$e->getMessage()}";
			Log::debug($message, compact('e'));
			return response()->json([
				"code"		=> 500,
				"message"	=> $message
			], 500);
		}
	}
}
