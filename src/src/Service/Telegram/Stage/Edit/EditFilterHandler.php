<?php
namespace App\Service\Telegram\Stage\Edit;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Telegram\Stage\Config;
use App\Service\Telegram\Stage\StageInterface;
use Doctrine\ORM\EntityManagerInterface;
use http\Client;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use PHPUnit\Util\Exception;

class EditFilterHandler
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }


    public function sendMenuForEdit(User $user)
    {
        $editProfileButtonName = [];
        $editProfileButtonName['text'] = 'Изменить фильтр по возрасту';
        $editProfileButtonName['callback_data'] = json_encode([
            'type' => 'filter',
            'action' => 'edit',
            'row_code' => CONFIG::FILTER_EDIT_AGE_CODE,
            'userId' => $user->getId()
        ], JSON_THROW_ON_ERROR);

        $editProfileButtonAge = [];
        $editProfileButtonAge['text'] = 'Изменить фильтр по полу';
        $editProfileButtonAge['callback_data'] = json_encode([
            'type' => 'filter',
            'action' => 'edit',
            'row_code' => CONFIG::FILTER_EDIT_GENDER_CODE,
            'userId' => $user->getId()
        ], JSON_THROW_ON_ERROR);



        $keyboards = new InlineKeyboard(
            [
                $editProfileButtonName
            ],
            [
                $editProfileButtonAge,
            ]
        );


        Request::sendMessage([
            'chat_id' => $user->getChatId(),
            'text'    => 'Выберите какой фильтр вы хотите изменить',
            'parse_mode' => 'HTML',
            'reply_markup' =>  $keyboards,
        ]);
    }

    public function setEditStageForUser(User $user, array $row)
    {
        $step = $row['row_code'] ?? null;

        if ($step === null) {
            throw new \Exception("There aren't a such row code");
        }

        $user->setStep($step);
        $user->setStage(CONFIG::FILTER_EDIT_STAGE);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $text = implode(PHP_EOL, Config::CONFIG[CONFIG::FILTER_EDIT_STAGE][$step]['message']);

        Request::sendMessage([
            'chat_id' => $user->getChatId(),
            'text'    => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}