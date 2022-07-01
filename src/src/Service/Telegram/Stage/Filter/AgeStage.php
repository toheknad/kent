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

class AgeStage implements StageInterface
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
        $age = $message['message']['text'];
        $chatId = $message['message']['chat']['id'];
        $age = $this->checkAge($age, $chatId);
        $this->saveUserData($age, $chatId, $nextStep);
    }

    /**
     * @throws \Exception
     */
    private function checkAge(string $age, int $chatId): array
    {
        $age = explode('-', $age);
        $ageMin = $age[0];
        $ageMax = $age[1];
        if ($ageMin < 15 ) {
            $this->sendAgeSmallError($chatId);
        }
        if ($ageMax > 100 ) {
            $this->sendAgeGreatError($chatId);
        }

        $this->sendSuccess($ageMin, $ageMax, $chatId);
        return ['ageMin' => $ageMin, 'ageMax' => $ageMax];
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

    private function sendSuccess(int $ageMin, int $ageMax, int $chatId)
    {
        $text = [];
        $text[] = "Теперь буду показывать людей с возрастом от {$ageMin} до {$ageMax}";
        $text[] = 'Отлично! Теперь можно приступить к поиску';
        $text[] = 'Для этого нажмите в меню "Поиск"';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'reply_markup' =>  Keyboard::getKeyboard(),
        ]);

    }

    private function saveUserData(array $age, int $chatId, int $nextStep)
    {
        $user = $this->userRepository->findOneBy(['chatId' => $chatId]);
        $user->resetStage();
        $userFilter = $user->getUserFilter();
        $userFilter->setAgeFrom($age['ageMin']);
        $userFilter->setAgeTo($age['ageMax']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public static function sendRetryMessage(int $chatId)
    {
        $text = [];
        $text[] = 'Введите возрастной диапазон для фильтра';
        $text[] = 'Например, 17-25';
        $text = implode(PHP_EOL, $text);

        Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => 'Markdown'
        ]);

    }


}