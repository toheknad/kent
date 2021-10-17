<?php
namespace App\Service\Telegram\Stage\Filter;

use App\Entity\User;
use App\Entity\UserFilter;
use App\Repository\UserFilterRepository;
use App\Repository\UserRepository;
use App\Service\Telegram\Keyboard\Keyboard;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class GenderStage implements StageInterface
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserFilterRepository $userFilterRepository;

    public function __construct(UserRepository $userRepository, UserFilterRepository $userFilterRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->userFilterRepository = $userFilterRepository;
    }

    /**
     * @throws \Exception
     */
    public function handle(User $user, array $message, int $nextStep)
    {
        $gender = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $gender = $this->checkGender($gender, $chatId);
        $this->saveUserData($gender, $chatId, $nextStep);
    }

    /**
     * @throws \Exception
     */
    private function checkGender(string $gender, int $chatId): string
    {
        if (!in_array(mb_strtolower($gender), ['мужской', 'женский', 'неважно'])) {
            $this->sendGenderError($chatId);
        }
        $this->sendSuccess($gender, $chatId);
        return $gender;
    }

    /**
     * @param int $chatId
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function sendGenderError(int $chatId)
    {
        $text = [];
        $text[] = 'Вы точно ввели правильный пол?';
        $text[] = 'Попробуйте еще раз';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
        throw new \Exception("User send wrong gender");
    }

    private function sendSuccess(string $gender, int $chatId)
    {
        $text = [];
        $text[] = "Хорошо, вы будете видеть людей только с полом: {$gender}";
        $text[] = 'И последнее, введите возрастной диапозон для фильтра';
        $text[] = 'Например, 17-25';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);

    }

    private function saveUserData(string $gender, int $chatId, int $nextStep)
    {
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $userFilter = new UserFilter();
        $userFilter->setGender($gender);
        $user->setUserFilter($userFilter);
        $user->setStep($nextStep);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}