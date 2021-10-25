<?php
namespace App\Service\Telegram\Strategy;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\Config;
use App\Service\Telegram\Stage\StageManager;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class CallbackQueryHandler implements MessageHandlerStrategyInterface
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
        $user = $this->userRepository->findBy(['chatId' => $message['callback_query']['from']['id']])[0];
        $this->handleAction($message, $user);
    }

    private function handleAction(array $message, User $user)
    {
        $callback = json_decode($message['callback_query']['data'], true);
        $action = $callback['action'];

        switch ($action) {
            case 'dislike':
                $this->dislike($user, $callback['userId']);
                break;
            case 'like':
                $this->like($user, $callback['userId']);
                break;
        }
    }

    private function dislike(User $userFrom, int $userSearch)
    {

    }

    private function like(User $userFrom, int $userSearch)
    {
    }
}