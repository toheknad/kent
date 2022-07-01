<?php
namespace App\Service\Telegram\Stage\Registration;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\Config;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

class AboutStage implements StageInterface
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
        $about = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $about = $this->checkAbout($about, $chatId);
        $this->saveUserData($about, $chatId, $nextStep);
    }

    /**
     * @throws \Exception
     */
    private function checkAbout(string $about, int $chatId): string
    {
        //TODO сделать проверку на город
        $this->sendSuccess($about, $chatId);
        return $about;
    }

    private function sendSuccess(string $about, int $chatId)
    {
        $text = [];
        $text[] = "Отлично, вы прошли базовую авторизацию";
        $text[] = 'Теперь давайте быстро настроим ваш фильтр поиска';
        $text[] = 'Чтобы я знал, кто вам нужен';
        $text[] = 'Для начала введите пол людей, которые должны вам попадаться';
        $text[] = 'Если вам все равно, то просто напишете "неважно"';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
    }

    private function saveUserData(string $about, int $chatId, int $nextStep)
    {
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $user->setStep(0);
        $user->setStage(Config::FILTER_STAGE);
        $user->setAbout($about);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public static function sendRetryMessage(int $chatId)
    {
        $text = [];
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


}