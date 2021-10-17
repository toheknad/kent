<?php
namespace App\Service\Telegram\Keyboard;

class Keyboard
{
    public static function getKeyboard()
    {
        return new \Longman\TelegramBot\Entities\Keyboard(
            ["🔒 Мой профиль" , "📓 Поиск"],
//            ["📓 Руководство", "📖 О сервисе	"],
            ["👁 Взаимные матчи"]
        );
    }
}