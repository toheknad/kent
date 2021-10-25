<?php
namespace App\Service\Telegram\Stage\Menu;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class SearchHandler
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    public function handle(User $user, array $message)
    {
        $city = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $userAfterFilter = $this->userRepository->getUserByFilter($user);
        $this->sendResultToUser($userAfterFilter, $chatId);

    }


    private function saveUserData(string $city, int $chatId, int $nextStep)
    {
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $user->setStep($nextStep);
        $user->setCity($city);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function sendResultToUser(User $userAfterFilter, int $chatId)
    {
        $text = [];
        $text[] = "<b>{$userAfterFilter->getName()} {$userAfterFilter->getSurname()}</b>";
        $text[] = "<b><i>Возраст</i></b>: {$userAfterFilter->getAge()}";
        $text[] = "<b><i>Пол</i></b>: {$userAfterFilter->getGender()}";
        $text[] = "<b><i>Город</i></b>: {$userAfterFilter->getCity()}";
        $text[] = "<b><i>Описание</i></b>: {$userAfterFilter->getAbout()}";
        $text = implode(PHP_EOL, $text);

        Request::sendPhoto([
            'chat_id' => $chatId,
            'photo'  => $userAfterFilter->getPhoto()
        ]);

        $likeButton = [];
        $likeButton['text'] = '👎';
        $likeButton['callback_data'] = json_encode(['type' => 'search', 'action' => 'dislike', 'userId' => $userAfterFilter->getId()]);

        $dislikeButton = [];
        $dislikeButton['text'] = '👍️';
        $dislikeButton['callback_data'] = json_encode(['type' => 'search', 'action' => 'like', 'userId' => $userAfterFilter->getId()]);

        $keyboards = new InlineKeyboard(
            [
                $likeButton,
                $dislikeButton
            ],
        );

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'HTML',
            'reply_markup' =>  $keyboards,
        ]);

    }


}