<?php

namespace App\Http\Controllers;

use App\Service\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AttachmentController extends Controller
{
    private AttachmentService $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function index(Request $request)
    {
        return $this->attachmentService->listAttachment($request->input('per_page', 15));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'nullable|file|max:5120',
            'file_size' => 'nullable|numeric|max:5242880',
        ]);
        return $this->attachmentService->createAttachment($request->all());
    }

    public function show(string $uuid)
    {
        return $this->attachmentService->getAttachment($uuid);
    }

    public function update(Request $request, string $uuid)
    {
        $request->validate([
            'file' => 'nullable|file|max:5120',
            'file_size' => 'nullable|numeric|max:5242880',
        ]);
        return $this->attachmentService->updateAttachment($uuid, $request->all());
    }

    public function destroy(string $uuid)
    {
        $this->attachmentService->deleteAttachment($uuid);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
    
    public function restore(string $uuid)
    {
        return $this->attachmentService->restoreAttachment($uuid);
    }
}