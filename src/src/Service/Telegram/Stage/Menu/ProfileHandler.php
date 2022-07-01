<?php
namespace App\Service\Telegram\Stage\Menu;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class ProfileHandler
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    public function handle(User $user, array $message)
    {
        $city = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $user = $this->userRepository->find($user);
        $this->sendResultToUser($user, $chatId);

    }

    private function sendResultToUser(User $userAfterFilter, int $chatId)
    {
        $text = [];
        $text[] = "<b>{$userAfterFilter->getName()} {$userAfterFilter->getSurname()}</b>";
        $text[] = "<b><i>Возраст</i></b>: {$userAfterFilter->getAge()}";
        $text[] = "<b><i>Пол</i></b>: {$userAfterFilter->getGender()}";
        $text[] = "<b><i>Город</i></b>: {$userAfterFilter->getCity()}";
        $text[] = "<b><i>Описание</i></b>: {$userAfterFilter->getAbout()}";
        $text = implode(PHP_EOL, $text);

        Request::sendPhoto([
            'chat_id' => $chatId,
            'photo'  => $userAfterFilter->getPhoto()
        ]);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'HTML',
        ]);

    }


}