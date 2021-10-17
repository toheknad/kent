<?php

namespace App\Service\Telegram\Stage;

use App\Entity\User;

class StageManager
{
    public function __construct(Config $config)
    {
    }

    /**
     * @throws \Exception
     */
    public function handle(User $user)
    {
        $currentStage = $user->getStage();
        $currentStep = $user->getStep();

        $this->hasUserStage($user);
    }

    /**
     * @throws \Exception
     */
    private function hasUserStage($user): void
    {
        if (!$user->getStage) {
            throw new \Exception("User hasn't active stage");
        }
    }
}