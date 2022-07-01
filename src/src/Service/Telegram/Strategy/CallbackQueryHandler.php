<?php
namespace App\Service\Telegram\Strategy;

use App\Entity\User;
use App\Entity\UserSearchResult;
use App\Repository\UserRepository;
use App\Service\Telegram\Message\MessageBuilder;
use App\Service\Telegram\Stage\Config;
use App\Service\Telegram\Stage\Edit\EditFilterHandler;
use App\Service\Telegram\Stage\Edit\EditProfileHandler;
use App\Service\Telegram\Stage\StageManager;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class CallbackQueryHandler implements MessageHandlerStrategyInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private StageManager $stageManager;
    private EditProfileHandler $editProfileHandler;
    private EditFilterHandler $editFilterHandler;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        StageManager $stageManager,
        EditProfileHandler $editProfileHandler,
        EditFilterHandler $editFilterHandler
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->stageManager = $stageManager;
        $this->editProfileHandler = $editProfileHandler;
        $this->editFilterHandler = $editFilterHandler;
    }

    public function process(array $message)
    {
        $user = $this->userRepository->findBy(['chatId' => $message['callback_query']['from']['id']])[0];
        $this->handleAction($message, $user);
    }

    private function handleAction(array $message, User $user)
    {
        $callback = json_decode($message['callback_query']['data'], true);
        $type = $callback['type'];
        $action = $callback['action'];
        $row = $callback;
        if ($type === 'profile') {
            switch ($action) {
                case 'menu':
                    $this->editProfileHandler->sendMenuForEdit($user);
                    break;
                case 'edit':
                    $this->editProfileHandler->setEditStageForUser($user, $row);
                    break;
            }
        } else if ($type === 'filter') {
            switch ($action) {
                case 'menu':
                    $this->editFilterHandler->sendMenuForEdit($user);
                    break;
                case 'edit':
                    $this->editFilterHandler->setEditStageForUser($user, $row);
                    break;
            }
        } else {
            $userTo = $this->userRepository->find($callback['userId']);
            switch ($action) {
                case 'dislike':
                    $this->dislike($user, $userTo);
                    break;
                case 'like':
                    $this->like($user, $userTo);
                    break;
            }
            $result = $this->userRepository->getUserByFilter($user);

            if ($result) {
                MessageBuilder::sendResultBySearchToUser($result[0], $user->getChatId());
            } else {
                MessageBuilder::sendNotFoundBySearch($user->getChatId());
            }
        }
    }

    private function dislike(User $userFrom, User $userSearch)
    {
        $userSearchResult = new UserSearchResult($userFrom, $userSearch, UserSearchResult::TYPE_DISLIKE);
        $this->entityManager->persist($userSearchResult);
        $this->entityManager->flush();
    }

    private function like(User $userFrom, User $userSearch)
    {
        $userSearchResult = new UserSearchResult($userFrom, $userSearch, UserSearchResult::TYPE_LIKE);
        $this->entityManager->persist($userSearchResult);
        $this->entityManager->flush();
    }
}