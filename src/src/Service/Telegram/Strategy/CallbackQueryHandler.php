<?php
namespace App\Service\Telegram\Strategy;

use App\Entity\User;
use App\Entity\UserSearchResult;
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
        $userTo = $this->userRepository->find($callback['userId']);
        switch ($action) {
            case 'dislike':
                $this->dislike($user, $userTo);
                break;
            case 'like':
                $this->like($user, $userTo);
                break;
        }
    }

    private function dislike(User $userFrom, User $userSearch)
    {
        $userSearchResult = new UserSearchResult($userFrom, $userSearch, 'dislike');
//        $this->entityManager->persist($userSearchResult);
//        $this->entityManager->flush();
        print_r($this->userRepository->getUserByFilterTest($userFrom));
    }

    private function like(User $userFrom, int $userSearch)
    {
        $userSearchResult = new UserSearchResult($userFrom->getId(), $userSearch);
    }
}