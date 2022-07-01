<?php
namespace App\Service\Telegram\Stage\Edit;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class AgeStage implements StageInterface
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
    public function handle(User $user, array $message, int $nextStep)
    {
        $age = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $age = $this->checkAge($age, $chatId);
        $this->saveUserData($age, $chatId, $nextStep);
    }

    /**
     * @throws \Exception
     */
    private function checkAge(string $age, int $chatId): string
    {
        if ($age < 15 ) {
            $this->sendAgeSmallError($chatId);
        }
        if ($age > 100 ) {
            $this->sendAgeGreatError($chatId);
        }

        $this->sendSuccess($age, $chatId);
        return $age;
    }

    /**
     * @param int $chatId
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function sendAgeSmallError(int $chatId)
    {
        $text = [];
        $text[] = 'К сожалению, вам должно быть больше 16 лет';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
        throw new \Exception("User send small age");
    }

    /**
     * @param int $chatId
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function sendAgeGreatError(int $chatId)
    {
        $text = [];
        $text[] = 'Вы точно ввели ваш настоящий возраст?';
        $text[] = 'Попробуйте еще раз';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
        throw new \Exception("User send great age");
    }

    private function sendSuccess(string $age, int $chatId)
    {
        $text = [];
        $text[] = "Отлично, вам {$age}!";
        $text[] = 'Теперь можете продолжить поиск';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
    }

    private function saveUserData(string $age, int $chatId, int $nextStep)
    {
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $user->setStep(0);
        $user->setStage('');
        $user->setAge($age);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public static function sendRetryMessage(int $chatId)
    {
        $text = [];
        $text[] = 'Теперь мне нужно узнать ваш возраст';
        $text[] = 'Введите, например, 20';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);

    }


}