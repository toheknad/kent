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

class EditProfileHandler
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
        $editProfileButtonName['text'] = 'Изменить имя';
        $editProfileButtonName['callback_data'] = json_encode([
            'type' => 'profile',
            'action' => 'edit',
            'row_code' => CONFIG::PROFILE_EDIT_NAME_CODE,
            'userId' => $user->getId()
        ], JSON_THROW_ON_ERROR);

        $editProfileButtonAge = [];
        $editProfileButtonAge['text'] = 'Изменить возраст';
        $editProfileButtonAge['callback_data'] = json_encode([
            'type' => 'profile',
            'action' => 'edit',
            'row_code' => CONFIG::PROFILE_EDIT_AGE_CODE,
            'userId' => $user->getId()
        ], JSON_THROW_ON_ERROR);

        $editProfileButtonCity = [];
        $editProfileButtonCity['text'] = 'Изменить город';
        $editProfileButtonCity['callback_data'] = json_encode([
            'type' => 'profile',
            'action' => 'edit',
            'row_code' => CONFIG::PROFILE_EDIT_CITY_CODE,
            'userId' => $user->getId()
        ], JSON_THROW_ON_ERROR);

        $editProfileButtonDescribe = [];
        $editProfileButtonDescribe['text'] = 'Изменить описание';
        $editProfileButtonDescribe['callback_data'] = json_encode([
            'type' => 'profile',
            'action' => 'edit',
            'row_code' => CONFIG::PROFILE_EDIT_DESCRIBE_CODE,
            'userId' => $user->getId()
        ], JSON_THROW_ON_ERROR);

        $editProfileButtonPhoto = [];
        $editProfileButtonPhoto['text'] = 'Изменить фото';
        $editProfileButtonPhoto['callback_data'] = json_encode([
            'type' => 'profile',
            'action' => 'edit',
            'row_code' => CONFIG::PROFILE_EDIT_PHOTO_CODE,
            'userId' => $user->getId()
        ], JSON_THROW_ON_ERROR);

        $keyboards = new InlineKeyboard(
            [
                $editProfileButtonName,
                $editProfileButtonAge,
            ],
            [
                $editProfileButtonCity,
                $editProfileButtonDescribe,
            ],
            [$editProfileButtonPhoto]
        );


        Request::sendMessage([
            'chat_id' => $user->getChatId(),
            'text'    => 'Выберите, что вы хотите поменять в профиле',
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
        $user->setStage(CONFIG::PROFILE_EDIT_STAGE);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $text = implode(PHP_EOL, Config::CONFIG[CONFIG::PROFILE_EDIT_STAGE][$step]['message']);

        Request::sendMessage([
            'chat_id' => $user->getChatId(),
            'text'    => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}