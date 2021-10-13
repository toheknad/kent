<?php

namespace App\Controller\Telegram;

use App\Service\Telegram\MessageHandleService;
use App\Service\Telegram\TelegramClient;
use Longman\TelegramBot\Telegram;
use Symfony\Component\HttpFoundation\JsonResponse;

class TelegramMessagesController
{
    private Telegram $telegram;
    private MessageHandleService $messageHandleService;

    public function __construct(TelegramClient $telegram, MessageHandleService $messageHandleService)
    {
        $this->telegram = $telegram->getClient();
        $this->messageHandleService = $messageHandleService;
    }

    public function index(): JsonResponse
    {
        $messages = $this->telegram->handleGetUpdates()->getRawData();
        foreach ($messages['result'] as $message) {
            $this->messageHandleService->start($message);
        }

        return new JsonResponse(['result' => 'ok!'], 201);
    }
}