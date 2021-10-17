<?php
namespace App\Service\Telegram\Stage;

use App\Service\Telegram\Stage\Registration\AgeStage;
use App\Service\Telegram\Stage\Registration\CityStage;
use App\Service\Telegram\Stage\Registration\FirstLastNameStage;

class Config
{
    public const REGISTRATION_STAGE = 'registration';

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
                'class' => CityStage::class
            ]
        ]
    ];
}