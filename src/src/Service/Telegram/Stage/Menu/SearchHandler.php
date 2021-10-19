<?php
namespace App\Service\Telegram\Stage\Menu;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Longman\TelegramBot\Request;

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
    public function handle(User $user, array $message, int $nextStep)
    {
        $city = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $city = $this->checkCity($city, $chatId);
        $this->saveUserData($city, $chatId, $nextStep);
    }

    /**
     * @throws \Exception
     */
    private function checkCity(string $city, int $chatId)
    {
        //TODO сделать проверку на город
        $this->sendSuccess($city, $chatId);
        return $city;
    }

//    /**
//     * @param int $chatId
//     * @throws \Longman\TelegramBot\Exception\TelegramException
//     */
//    private function sendCountError(int $chatId)
//    {
//        $text = [];
//        $text[] = 'Вы точно указали правильно Имя и Фамилию?';
//        $text[] = 'Попробуйте, пожалуйста, еще раз';
//        $text[] = 'Введите Имя и Фамилию';
//        $text[] = 'Например, Иван Петров';
//        $text = implode(PHP_EOL, $text);
//
//        Request::sendMessage([
//            'chat_id' => $chatId,
//            'text'    => $text,
//            'parse_mode' => 'Markdown'
//        ]);
//        throw new \Exception("User send wrong first and last name. it's count error");
//    }

    private function sendSuccess(string $city, int $chatId)
    {
        $text = [];
        $text[] = "Ваш город - {$city}, я запомнил!";
        $text[] = 'Осталось совсем чуть-чуть';
        $text[] = 'Теперь мне нужно узнать ваш возраст';
        $text[] = 'Введите, например, 20';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);
    }

    private function saveUserData(string $city, int $chatId, int $nextStep)
    {
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $user->setStep($nextStep);
        $user->setCity($city);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }


}