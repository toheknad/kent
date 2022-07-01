<?php
namespace App\Service\Telegram\Strategy;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Keyboard\Keyboard;
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
            if ($userText === '/start') {
                $text[] = 'Привет!';
                $text[] = 'Я бот, который поможет найти тебе лучшего соседа.';
                $text[] = 'Чтобы начать мне нужно узнать о тебе кое-что';
                $text[] = 'Для начала введи свою имя и фамилию';
            }
            $text = implode(PHP_EOL, $text);

            Request::sendMessage([
                'chat_id' => $chatId,
                'text'    => $text,
                'parse_mode' => 'Markdown'
            ]);

//            Request::sendMessage([
//                'chat_id' => $chatId,
//                'text'    => "323",
//                'reply_markup' =>  Keyboard::getKeyboard(),
//            ]);

            /** @var User $user */
            $user = $this->userRepository->findOneBy(['chatId' => $message['message']['from']['id']]);
            if (!$user) {
                $user = new User($message['message']['from']['id']);
            }
            $user->setStage(Config::REGISTRATION_STAGE);
            $user->setStep(0);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}