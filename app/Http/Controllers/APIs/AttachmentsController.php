<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
				"files"	=> "required|array",
				"files.*"	=> "file",
			]);

			$files = $request->file("files");

			$filesResponses = [];
			array_walk($files, function ($file) use (&$filesResponses) {
				$filePath = Storage::putFile("attachments", $file);

				$attachment = Attachment::create([
					'original_file_name'	=> $file->getClientOriginalName(),
					'file_path'				=> $filePath
				]);
				
				$filesResponses[] = $attachment->formatForApi();
			});

			return $this->successResponse($filesResponses, 201, "Uploaded attachment successfully.");
		} catch(Exception $e) {
			$message = "An error occurred while uploading attachments: {$e->getMessage()}";
			Log::debug($message, compact('e'));
			return $this->errorResponse(500, $message);
		}
	}
}
