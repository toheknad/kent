<?php
namespace App\Service\Telegram\Stage;

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
                'info' => 'user wrote first and last name',
                'class'
            ]
        ]
    ];
}