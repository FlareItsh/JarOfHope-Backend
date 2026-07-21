<?php

namespace App\Http\Controllers;

use App\Service\MessageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    private MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Request $request)
    {
        return $this->messageService->listMessage($request->input('per_page', 15));
    }

    public function store(Request $request)
    {
        return $this->messageService->createMessage($request->all());
    }

    public function show(string $uuid)
    {
        return $this->messageService->getMessage($uuid);
    }

    public function update(Request $request, string $uuid)
    {
        return $this->messageService->updateMessage($uuid, $request->all());
    }

    public function destroy(string $uuid)
    {
        $this->messageService->deleteMessage($uuid);
        return response()->json(['message' => 'Deleted successfully'], 200);
    }

    public function restore(string $uuid)
    {
        return $this->messageService->restoreMessage($uuid);
    }
}
