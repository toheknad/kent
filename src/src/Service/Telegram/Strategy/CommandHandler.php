<?php
namespace App\Service\Telegram\Strategy;

use Longman\TelegramBot\Request;

class CommandHandler implements MessageHandlerStrategyInterface
{

    public function process(array $message)
    {
        if ($message['message']['text'] === '/start') {
            $userText = $message['message']['text'];
            $chatId = $message['message']['chat']['id'];
            $text = [];
            if ($userText == '/start') {
                $text[] = 'Привет!';
                $text[] = 'Я бот, который поможет найти тебе лучшего соседа.';
                $text[] = 'Чтобы начать мне нужно узнать о тебе кое-что';
                $text[] = 'Для начала введи свою фамилию и имя';
            }
            $text = implode(PHP_EOL, $text);

            Request::sendMessage([
                'chat_id' => $chatId,
                'text'    => $text,
                'parse_mode' => 'Markdown'
            ]);
        }
    }
}