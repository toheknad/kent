<?php

namespace App\Service\Telegram;

use Longman\TelegramBot\Request;

class MessageHandleService
{
    public function start(array $message)
    {
        echo "<pre>";
        print_r($message);
        echo "</pre>";
        $userText = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $text = [];
        if ($userText == '/start') {
            $text[] = 'Привет!';
            $text[] = 'Я бот, который будет тебе напоминать, когда нужно повторять слова, которые ты учишь в TWOMX.';
            $text[] = 'Введите логин и пароль после комманды, например';
            $text[] = '/login my-account@google.com 12345678';

        }
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
 
    }
}