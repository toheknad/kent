<?php

namespace App\Service\Telegram\Stage;

use App\Entity\User;

interface StageInterface
{
    public function handle(User $user);
}