<?php
namespace App\Service\Telegram\Stage\Registration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class GenderStage implements StageInterface
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
        if (!in_array(mb_strtolower($gender), ['мужской', 'женский'])) {
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
        $text[] = "Отлично, вы прошли базовую авторизацию";
        $text[] = 'Вы уже можете перейти к поиску';
        $text[] = 'Но если выхотите уточнить информацию о себе';
        $text[] = 'Например: наличие домашних животных, вредные привычки и тд';
        $text[] = 'То перейдите в "Расширенные настройки" в меню';
        $text[] = 'Удачного поиска!';
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
        $user->resetStage($nextStep);
        $user->setGender($gender);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}