<?php
namespace App\Service\Telegram\Stage\Registration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class FirstLastNameStage implements StageInterface
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
        $name = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $this->checkFirstAndLastName($name, $chatId);
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $user->setStep($nextStep);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @throws \Exception
     */
    private function checkFirstAndLastName(string $name, int $chatId)
    {
        $name = explode(' ', $name);
        $countWords = count($name);
        if (!($countWords > 1) || !($countWords < 5)) {
            $this->sendCountError($chatId);
        }
        $this->sendSuccess($name, $chatId);
    }

    /**
     * @param int $chatId
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    private function sendCountError(int $chatId)
    {
        $text = [];
        $text[] = 'Вы точно указали правильно Имя и Фамилию?';
        $text[] = 'Попробуйте, пожалуйста, еще раз';
        $text[] = 'Введите Имя и Фамилию';
        $text[] = 'Например, Иван Петров';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
        throw new \Exception("User send wrong first and last name. it's count error");
    }

    private function sendSuccess(array $name, int $chatId)
    {
        $text = [];
        $text[] = "Отлично, {$name[0]}!";
        $text[] = 'Теперь введите город';
        $text[] = 'Например, Санкт-Питербург';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
    }


}