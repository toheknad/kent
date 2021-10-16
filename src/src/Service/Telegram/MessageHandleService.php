<?php

namespace App\Service\Telegram;

use App\Repository\UserRepository;
use App\Service\Telegram\Strategy\CommandHandler;
use App\Service\Telegram\Strategy\TextHandler;
use Longman\TelegramBot\Request;

class MessageHandleService
{
    private CommandHandler $commandHandler;
    private TextHandler $textHandler;
    private UserRepository $userRepository;

    /**
     * @param CommandHandler $commandHandler
     * @param TextHandler $textHandler
     */
    public function __construct(CommandHandler $commandHandler, TextHandler $textHandler, UserRepository $userRepository)
    {
        $this->commandHandler = $commandHandler;
        $this->textHandler = $textHandler;
        $this->userRepository = $userRepository;
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
        } else {
            $this->textHandler->process($message);
        }
    }

    private function identificationUser(array $message)
    {
//        $this->userRepository->findBy(['chatId' => $message[]])
    }
}