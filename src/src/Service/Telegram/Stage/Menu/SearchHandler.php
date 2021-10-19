<?php
namespace App\Service\Telegram\Stage\Menu;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class SearchHandler
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
        $userAfterFilter = $this->userRepository->getUserByFilter($user);
        $this->sendResultToUser($userAfterFilter, $chatId);

    }


    private function saveUserData(string $city, int $chatId, int $nextStep)
    {
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $user->setStep($nextStep);
        $user->setCity($city);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function sendResultToUser(User $userAfterFilter, int $chatId)
    {
        $text = [];
        $text[] = "{$userAfterFilter->getName()} {$userAfterFilter->getSurname()}";
        $text[] = "Возраст: {$userAfterFilter->getAge()}";
        $text[] = "Пол: {$userAfterFilter->getGender()}";
        $text[] = "Город: {$userAfterFilter->getCity()}";
        $text = implode(PHP_EOL, $text);

        Request::sendPhoto([
            'chat_id' => $chatId,
            'photo'  => 'AgACAgIAAxkBAAIBU2Fu_D-NR4mHWX1uVWN1fO0qBludAAL6szEbBfB4SzFUyBKa7XOEAQADAgADbQADIQQ'
        ]);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);

    }


}