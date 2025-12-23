<?php

namespace Tests\Feature;

use App\Enums\DocumentType;
use App\Models\Load;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DocumentUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_document_can_be_uploaded_to_load(): void
    {
        Storage::fake('documents');

        $user = User::factory()->create();
        $load = Load::factory()->create();

        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->image('pod.jpg');

        $response = $this->postJson("/api/loads/{$load->id}/documents", [
            'document' => $file,
            'type' => DocumentType::POD->value,
        ]);

        $response->assertStatus(201)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('load_documents', [
            'load_id' => $load->id,
            'type' => DocumentType::POD->value,
            'uploaded_by' => $user->id,
        ]);

        Storage::disk('documents')->assertExists(
            $load->documents()->first()->path
        );
    }

    public function test_document_upload_validates_file_type(): void
    {
        Storage::fake('documents');

        $user = User::factory()->create();
        $load = Load::factory()->create();

        Sanctum::actingAs($user);

        $file = UploadedFile::fake()->create('document.exe', 100);

        $response = $this->postJson("/api/loads/{$load->id}/documents", [
            'document' => $file,
            'type' => DocumentType::POD->value,
        ]);

        $response->assertStatus(422);
    }

    public function test_document_upload_validates_file_size(): void
    {
        Storage::fake('documents');

        $user = User::factory()->create();
        $load = Load::factory()->create();

        Sanctum::actingAs($user);

        // Create file larger than 10MB
        $file = UploadedFile::fake()->create('large.pdf', 11000);

        $response = $this->postJson("/api/loads/{$load->id}/documents", [
            'document' => $file,
            'type' => DocumentType::POD->value,
        ]);

        $response->assertStatus(422);
    }
}
