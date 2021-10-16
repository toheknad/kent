<?php
namespace App\Service\Telegram\Strategy;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\Config;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class CommandHandler implements MessageHandlerStrategyInterface
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

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

            /** @var User $user */
            if ($user = $this->userRepository->findBy(['chatId' => $message['message']['from']['id']])) {
                $user = $user[0];
                $user->setStage(Config::REGISTRATION_STAGE, 0);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }
        }
    }
}