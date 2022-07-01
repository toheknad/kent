<?php

namespace App\Service\Telegram;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Message\MessageBuilder;
use App\Service\Telegram\Stage\Config;
use App\Service\Telegram\Stage\StageManager;
use App\Service\Telegram\Strategy\CallbackQueryHandler;
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
    private CallbackQueryHandler $callbackQueryHandler;
    private StageManager $stageManager;

    /**
     * @param CommandHandler $commandHandler
     * @param TextHandler $textHandler
     */
    public function __construct(EntityManagerInterface $entityManager,
                                CommandHandler $commandHandler,
                                TextHandler $textHandler,
                                UserRepository $userRepository,
                                MenuHandler $menuHandler,
                                CallbackQueryHandler $callbackQueryHandler,
                                StageManager $stageManager
    )
    {
        $this->commandHandler = $commandHandler;
        $this->textHandler = $textHandler;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->menuHandler = $menuHandler;
        $this->callbackQueryHandler = $callbackQueryHandler;
        $this->stageManager = $stageManager;
    }

    /**
     * @param array $message
     */
    public function start(array $message)
    {
        echo "<pre>";
        print_r($message);
        echo "</pre>";
        //$this->identificationUser($message);
        $this->handleMessageByType($message);

    }

    /**
     * @param array $message
     */
    private function handleMessageByType(array $message)
    {
        // если юзер еще не заполнил профиль
        if ($this->isFirstMessageFromUser($message) && $message['message']['text'][0] !== '/') {
            MessageBuilder::sendWelcomeMessage($message['message']['from']['id']);
            return;
        }

        try {
            if (isset($message['message']['text']) && $message['message']['text'][0] === '/') {
                $this->commandHandler->process($message);
            } elseif (isset($message['message']['text']) && isset(Config::MENU[$message['message']['text']])) {
                $this->menuHandler->process($message);
            } elseif (isset($message['callback_query'])) {
                $this->callbackQueryHandler->process($message);
            } else {
                $this->textHandler->process($message);
            }
        } catch (\Exception $exception) {
            throw $exception;
//            if (!$user = $this->userRepository->findOneBy(['chatId' => $message['message']['from']['id']])) {
//                return;
//            }
//
//            if ($user->getStep() !== 0 || !$user->getStage()) {
//                $className = $this->stageManager->defineHandlerByUser($user);
//                $className::sendRetryMessage($user->getChatId());
//            }
        }
    }

    private function isFirstMessageFromUser(array $message)
    {
        if (isset($message['callback_query'])) {
            return false;
        }
        $telegramUserId = $message['message']['from']['id'];
        /** @var User $user */
        if (!$user = $this->userRepository->findOneBy(['chatId' => $telegramUserId])) {
            $user = new User($telegramUserId);
            $user->setStage(Config::WELCOME_STAGE_CODE);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        }

        if ($user->getStage() === Config::WELCOME_STAGE_CODE) {
            return true;
        }

        return false;
    }
}