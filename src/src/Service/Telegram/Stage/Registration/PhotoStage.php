<?php
namespace App\Service\Telegram\Stage\Registration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class PhotoStage implements StageInterface
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
        $chatId = $message['message']['chat']['id'];
        if (!isset($message['message']['photo'])) {
            $this->sendErrorTypeMessage($chatId);
        }
        $photo = $message['message']['photo'];
        $photo = $this->checkPhoto($photo, $chatId);
        $this->saveUserData($photo, $chatId, $nextStep);
    }

    /**
     * @throws \Exception
     */
    private function checkPhoto(array $photo, int $chatId)
    {
        if (!isset($photo[2]['file_id'])) {
            print_r('3213');
                $this->sendErrorTypeMessage($chatId);
        }
        $this->sendSuccess($chatId);
        return $photo[2]['file_id'];
    }

    private function sendSuccess(int $chatId)
    {
        $text = [];
        $text[] = "Отлично! Я сохранил ваше фото";
        $text[] = 'Теперь напишите чтобы нибудь о себе';
        $text[] = 'Это описание люди будут видеть при виде вашей анкеты';
        $text[] = '---- Пример ----';
        $text[] = 'Всем привет! Меня зовут Иван Иванов, ищу себе соседа';
        $text[] = 'недалеко от центра в питере, бюджет квартиры до 30к.';
        $text[] = 'Домашних животных нет';
        $text[] = 'Вредных привычек тоже не имею';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
    }

    private function saveUserData(string $photo, int $chatId, int $nextStep)
    {
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $user->setStep($nextStep);
        $user->setPhoto($photo);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function sendErrorTypeMessage(int $chatId)
    {
        $text = [];
        $text[] = 'Вы точно скинули фото?';
        $text[] = 'Попробуйте, пожалуйста, еще раз';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
        throw new \Exception("User send not photo");
    }


}