<?php
namespace App\Service\Telegram\Stage;

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
                'class' => \App\Service\Telegram\Stage\Filter\AgeStage::class
            ],
        ]
    ];

    public const MENU = [
        "🔒 Мой профиль" => true,
        "📓 Поиск" => SearchHandler::class,
        "👁 Взаимные матчи" => true
    ];
}