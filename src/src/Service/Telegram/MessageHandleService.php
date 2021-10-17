<?php

namespace App\Service\Telegram;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\Config;
use App\Service\Telegram\Strategy\CommandHandler;
use App\Service\Telegram\Strategy\TextHandler;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class MessageHandleService
{
    private CommandHandler $commandHandler;
    private TextHandler $textHandler;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param CommandHandler $commandHandler
     * @param TextHandler $textHandler
     */
    public function __construct(EntityManagerInterface $entityManager, CommandHandler $commandHandler, TextHandler $textHandler, UserRepository $userRepository)
    {
        $this->commandHandler = $commandHandler;
        $this->textHandler = $textHandler;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $message
     */
    public function start(array $message)
    {
        echo "<pre>";
        print_r($message);
        echo "</pre>";
        $this->identificationUser($message);
        $this->handleMessageByType($message);

    }

    /**
     * @param array $message
     */
    private function handleMessageByType(array $message)
    {
        if ($message['message']['text'][0] === '/') {
            $this->commandHandler->process($message);
        } elseif (in_array($message['message']['text'],Config::MENU)) {
            print_r('Зашли в меню');
            die();
        } else {
            $this->textHandler->process($message);
        }
    }

    private function identificationUser(array $message)
    {
        $telegramUserId = $message['message']['from']['id'];
        if (!$this->userRepository->findBy(['chatId' => $telegramUserId])) {
            $user = new User($telegramUserId);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}