<?php
namespace App\Service\Telegram\Stage;

use App\Service\Telegram\Stage\Menu\ProfileHandler;
use App\Service\Telegram\Stage\Menu\SearchHandler;
use App\Service\Telegram\Stage\Registration\AboutStage;
use App\Service\Telegram\Stage\Registration\AgeStage;
use App\Service\Telegram\Stage\Registration\CityStage;
use App\Service\Telegram\Stage\Registration\FirstLastNameStage;
use App\Service\Telegram\Stage\Registration\GenderStage;
use App\Service\Telegram\Stage\Registration\PhotoStage;

class Config
{
    public const REGISTRATION_STAGE = 'registration';
    public const FILTER_STAGE = 'filter';

    public const PROFILE_EDIT_STAGE = 'profile_edit';
    public const PROFILE_EDIT_NAME_CODE = '0';
    public const PROFILE_EDIT_AGE_CODE = '1';
    public const PROFILE_EDIT_CITY_CODE = '2';
    public const PROFILE_EDIT_GENDER_CODE = '3';
    public const PROFILE_EDIT_PHOTO_CODE = '4';
    public const PROFILE_EDIT_DESCRIBE_CODE = '5';

    public const FILTER_EDIT_STAGE = 'filter_edit';
    public const FILTER_EDIT_AGE_CODE = '0';
    public const FILTER_EDIT_GENDER_CODE = '1';

    public const WELCOME_STAGE_CODE = '100';

    public const CONFIG = [
        self::REGISTRATION_STAGE => [
            '0' => [
                'info'  => 'user wrote /start',
                'class' => null,
            ],
            '1' => [
                'info'  => 'user wrote first and last name',
                'class' => FirstLastNameStage::class
            ],
            '2' => [
                'info'  => 'user wrote city',
                'class' => CityStage::class
            ],
            '3' => [
                'info'  => 'user wrote age',
                'class' => AgeStage::class
            ],
            '4' => [
                'info'  => 'user wrote gender',
                'class' => GenderStage::class
            ],
            '5' => [
                'info'  => 'user send photo',
                'class' => PhotoStage::class
            ],
            '6' => [
                'info'  => 'user wrote about',
                'class' => AboutStage::class
            ]
        ],
        self::FILTER_STAGE => [
            '0' => [
                'info'  => 'user completed auth',
                'class' => null,
            ],
            '1' => [
                'info'  => 'user wrote gender for filter ',
                'class' => \App\Service\Telegram\Stage\Filter\GenderStage::class
            ],
            '2' => [
                'info'  => 'user wrote age for filter',
                'retryMessage' => 'Вам',
                'class' => \App\Service\Telegram\Stage\Filter\AgeStage::class
            ],
        ],
        self::PROFILE_EDIT_STAGE => [
            self::PROFILE_EDIT_NAME_CODE => [
                'info'  => 'user is changing name',
                'message' => [
                    'Напишите свое имя',
                    'Например, Санкт-Петербург'
                ],
                'class' => \App\Service\Telegram\Stage\Edit\FirstLastNameStage::class,
            ],
            self::PROFILE_EDIT_AGE_CODE => [
                'info'  => 'user is changing age',
                'message' => [
                    'Теперь мне нужно узнать ваш возраст',
                    'Например, 22'
                ],
                'class' => \App\Service\Telegram\Stage\Edit\AgeStage::class,
            ],
            self::PROFILE_EDIT_CITY_CODE => [
                'info'  => 'user is changing city',
                'message' => [
                    'Теперь введите город',
                    'Например, Санкт-Петербург'
                ],
                'class' => \App\Service\Telegram\Stage\Edit\CityStage::class,
            ],
            self::PROFILE_EDIT_GENDER_CODE => [
                'info'  => 'user is changing gender',
                'message' => [
                    'Ваш пол?',
                    'Например, женский'
                ],
                'class' => \App\Service\Telegram\Stage\Edit\GenderStage::class,
            ],
            self::PROFILE_EDIT_PHOTO_CODE => [
                'info'  => 'user is changing photo',
                'message' => [
                    "Отлично, теперь пришлите мне фото, которое люди будут видеть",
                    "при показе вашей анкеты"
                ],
                'class' => \App\Service\Telegram\Stage\Edit\PhotoStage::class,
            ],
            self::PROFILE_EDIT_DESCRIBE_CODE => [
                'info'  => 'user is changing description',
                'message' => [
                    'Теперь напишите чтобы нибудь о себе',
                    'Это описание люди будут видеть при виде вашей анкеты',
                    '---- Пример ----',
                    'Всем привет! Меня зовут Иван Иванов, ищу себе соседа',
                    'недалеко от центра в питере, бюджет квартиры до 30к.',
                    'Домашних животных нет',
                    'Вредных привычек тоже не имею'
                ],
                'class' => \App\Service\Telegram\Stage\Edit\AboutStage::class,
            ],
        ],
        self::FILTER_EDIT_STAGE => [
            self::FILTER_EDIT_AGE_CODE => [
                'info'  => 'user is changing age',
                'message' => [
                    'Введите возрастной диапазон для фильтра',
                    'Например, 17-25'
                ],
                'class' => \App\Service\Telegram\Stage\Edit\Filter\AgeStage::class,
            ],
            self::FILTER_EDIT_GENDER_CODE => [
                'info'  => 'user is changing gender',
                'message' => [
                    'Введите пол людей, которые должны вам попадаться',
                    'Если вам все равно, то просто напишете "неважно"'
                ],
                'class' => \App\Service\Telegram\Stage\Edit\Filter\GenderStage::class,
            ]
        ]
    ];

    public const MENU = [
        "🔒 Мой профиль" => ProfileHandler::class,
        "📓 Поиск" => SearchHandler::class,
        "👁 Взаимные матчи" => true
    ];
}