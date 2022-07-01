<?php
namespace App\Service\Telegram\Message;

use App\Entity\User;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;

class MessageBuilder
{
    public static function sendResultBySearchToUser(User $userBySearch, int $chatId)
    {
        $text = [];
        $text[] = "<b>{$userBySearch->getName()} {$userBySearch->getSurname()}</b>";
        $text[] = "<b><i>Возраст</i></b>: {$userBySearch->getAge()}";
        $text[] = "<b><i>Пол</i></b>: {$userBySearch->getGender()}";
        $text[] = "<b><i>Город</i></b>: {$userBySearch->getCity()}";
        $text[] = "<b><i>Описание</i></b>: {$userBySearch->getAbout()}";
        $text = implode(PHP_EOL, $text);

        Request::sendPhoto([
            'chat_id' => $chatId,
            'photo'  => $userBySearch->getPhoto()
        ]);

        $likeButton = [];
        $likeButton['text'] = '👎';
        $likeButton['callback_data'] = json_encode(['type' => 'search', 'action' => 'dislike', 'userId' => $userBySearch->getId()]);

        $dislikeButton = [];
        $dislikeButton['text'] = '👍️';
        $dislikeButton['callback_data'] = json_encode(['type' => 'search', 'action' => 'like', 'userId' => $userBySearch->getId()]);

        $keyboards = new InlineKeyboard(
            [
                $likeButton,
                $dislikeButton
            ],
        );

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'HTML',
            'reply_markup' =>  $keyboards,
        ]);
    }

    public static function sendNotFoundBySearch(int $chatId)
    {
        $text = [];
        $text[] = "<b>К сожалению не удалось найти новых анкет</b>";
        $text = implode(PHP_EOL, $text);


        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'HTML',
        ]);
    }


    public static function sendWelcomeMessage(int $chatId)
    {
        $text = [];
        $text[] = "<b>Добрый день!</b>";
        $text[] = "<b>Видимо, у вас все еще нет профиля</b>";
        $text[] = "<b>Напишите /start чтобы начать</b>";
        $text = implode(PHP_EOL, $text);


        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    public static function sendAuthErrorMessage(int $chatId)
    {
        $text = [];
        $text[] = "<b>Для доступа к этому функционалу нужно быть полностью авторизованным</b>";
        $text = implode(PHP_EOL, $text);


        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}