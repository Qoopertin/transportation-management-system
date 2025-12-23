<?php

namespace App\Http\Controllers\Api;

use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadLoadDocumentRequest;
use App\Models\Load;
use App\Services\LoadDocumentService;
use Illuminate\Http\JsonResponse;

class LoadDocumentController extends Controller
{
    public function __construct(private LoadDocumentService $documentService)
    {
        $this->middleware(['auth:sanctum']);
    }

    public function store(UploadLoadDocumentRequest $request, Load $load): JsonResponse
    {
        try {
            $document = $this->documentService->uploadDocument(
                $load,
                $request->file('document'),
                DocumentType::from($request->type),
                $request->user()->id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    'id' => $document->id,
                    'type' => $document->type->label(),
                    'original_name' => $document->original_name,
                    'size_mb' => $document->size_in_mb,
                    'uploaded_at' => $document->created_at->toIso8601String(),
                ],
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
