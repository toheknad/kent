<?php
namespace App\Service\Telegram;

use Longman\TelegramBot\Telegram;

class TelegramClient
{
    public function getClient(): Telegram
    {
        $bot_api_key = $_ENV['TELEGRAM_TOKEN'];
        $bot_username = $_ENV['TELEGRAM_BOT_NAME'];
        if($_ENV['APP_ENV'] == 'true') {
            return (new Telegram($bot_api_key, $bot_username))->useGetUpdatesWithoutDatabase();
        } else {
            print_r('13');
            $telegram = new Telegram($bot_api_key, $bot_username);
            return $telegram->useGetUpdatesWithoutDatabase();
        }
    }

}