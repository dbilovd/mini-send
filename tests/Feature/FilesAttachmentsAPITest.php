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
            "files" => [
                UploadedFile::fake()->image('fake-photo.jpg')
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    "*" => [
                        "attachmentId",
                        "fileName",
                        "filePath",
                        "downloadLink",
                        "createdAt"
                    ]
                ]
            ]);
        $responseObj = json_decode(
            (string) $response->getContent()
        );

        array_walk($responseObj->data, function ($attachment) use ($response) {
            $response->assertJsonFragment([
                "fileName"  => "fake-photo.jpg",
            ]);

            Storage::assertExists($attachment->filePath);
        });
    }

    /** @test */
    public function it_can_upload_more_than_one_file_as_attachments()
    {
        $this->withoutExceptionHandling();
        Storage::fake();
        
        $filesToUpload = [
            UploadedFile::fake()->image('fake-photo.jpg'),
            UploadedFile::fake()->image('fake-photo-2.jpg'),
        ];
        $response = $this->postJson("/api/attachments", [
            "files" => $filesToUpload
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "code",
                "message",
                "data" => [
                    "*" => [
                        "attachmentId",
                        "fileName",
                        "filePath",
                        "downloadLink",
                        "createdAt"
                    ]
                ]
            ]);
        $responseObj = json_decode(
            (string) $response->getContent()
        );

        array_walk($responseObj->data, function ($attachment) {
            Storage::assertExists($attachment->filePath);
        });

        array_walk($filesToUpload, function ($file) use ($response) {
            $response->assertJsonFragment([
                "fileName"  => "fake-photo.jpg",
            ]);
        });
    }
}
