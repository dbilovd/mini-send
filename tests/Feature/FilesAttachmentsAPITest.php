<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilesAttachmentsAPITest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_file_can_be_uploaded_as_an_attachment()
    {
        $this->withoutExceptionHandling();
        Storage::fake();

        $response = $this->postJson("/api/attachments", [
            "file" => UploadedFile::fake()->image('fake-photo.jpg')
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    "attachmentId",
                    "path",
                    "createdAt",
                ]
            ]);
        $responseObj = json_decode(
            (string) $response->getContent()
        );

        Storage::assertExists($responseObj->data->path);
    }
}
