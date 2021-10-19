<?php

namespace App\Service\Telegram;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\Config;
use App\Service\Telegram\Strategy\CommandHandler;
use App\Service\Telegram\Strategy\MenuHandler;
use App\Service\Telegram\Strategy\TextHandler;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class MessageHandleService
{
    private CommandHandler $commandHandler;
    private TextHandler $textHandler;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private MenuHandler $menuHandler;

    /**
     * @param CommandHandler $commandHandler
     * @param TextHandler $textHandler
     */
    public function __construct(EntityManagerInterface $entityManager,
                                CommandHandler $commandHandler,
                                TextHandler $textHandler,
                                UserRepository $userRepository,
                                MenuHandler $menuHandler
    )
    {
        $this->commandHandler = $commandHandler;
        $this->textHandler = $textHandler;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->menuHandler = $menuHandler;
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
        if (isset($message['message']['text']) && $message['message']['text'][0] === '/') {
            $this->commandHandler->process($message);
        } elseif (isset($message['message']['text']) && isset(Config::MENU[$message['message']['text']])) {
            $this->menuHandler->process($message);
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