<?php
namespace App\Service\Telegram\Strategy;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class TextHandler implements MessageHandlerStrategyInterface
{
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function process(array $message)
    {
        $
    }
}