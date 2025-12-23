<?php

namespace App\Services;

use App\Enums\DocumentType;
use App\Models\Load;
use App\Models\LoadDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LoadDocumentService
{
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/jpg',
        'image/webp',
    ];

    private const MAX_FILE_SIZE = 10240; // 10MB in KB

    public function uploadDocument(Load $load, UploadedFile $file, DocumentType $type, int $uploadedBy): LoadDocument
    {
        // Validate file
        $this->validateFile($file);

        // Generate safe filename
        $filename = $this->generateFilename($file);
        $path = "load_{$load->id}/{$filename}";

        // Store file
        Storage::disk('documents')->put($path, $file->getContent());

        // Create database record
        $document = LoadDocument::create([
            'load_id' => $load->id,
            'type' => $type,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => $uploadedBy,
        ]);

        \Log::info('Document uploaded', [
            'load_id' => $load->id,
            'document_id' => $document->id,
            'type' => $type->value,
            'size' => $file->getSize(),
        ]);

        return $document;
    }

    public function deleteDocument(LoadDocument $document): bool
    {
        // Delete file from storage
        Storage::disk('documents')->delete($document->path);

        // Delete database record
        return $document->delete();
    }

    private function validateFile(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new \InvalidArgumentException('File type not allowed. Only PDF and images (JPEG, PNG, WebP) are accepted.');
        }

        if ($file->getSize() > self::MAX_FILE_SIZE * 1024) {
            throw new \InvalidArgumentException('File size exceeds maximum allowed size of ' . self::MAX_FILE_SIZE . 'KB.');
        }
    }

    private function generateFilename(UploadedFile $file): string
    {
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);
        $extension = $file->getClientOriginalExtension();
        
        return "{$timestamp}_{$random}.{$extension}";
    }
}
