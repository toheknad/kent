<?php
namespace App\Service\Telegram\Strategy;

use App\Repository\UserRepository;
use App\Service\Telegram\Stage\Config;
use App\Service\Telegram\Stage\StageManager;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class MenuHandler implements MessageHandlerStrategyInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private StageManager $stageManager;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, StageManager $stageManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->stageManager = $stageManager;
    }

    public function process(array $message)
    {
        $user = $this->userRepository->findBy(['chatId' => $message['message']['from']['id']])[0];
        $handlerName = Config::MENU[$message['message']['text']];
        print_r($handlerName);

        (new $handlerName($this->userRepository, $this->entityManager))->handle($user, $message);
    }
}