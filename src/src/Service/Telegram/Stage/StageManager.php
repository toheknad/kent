<?php

namespace App\Service\Telegram\Stage;

use App\Entity\User;
use App\Repository\UserFilterRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class StageManager
{
    private Config $config;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UserFilterRepository $userFilterRepository;

    public function __construct(Config $config, UserRepository $userRepository, UserFilterRepository $userFilterRepository, EntityManagerInterface $entityManager)
    {
        $this->config = $config;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->userFilterRepository = $userFilterRepository;
    }

    /**
     * @throws \Exception
     */
    public function handle(User $user, array $message)
    {
        $currentUserStage = $this->hasUserStage($user);
        $currentUserStep = $user->getStep();
        $nextStep = $currentUserStep + 1;


        // регистрация и фильтр
        switch ($currentUserStage){
            case Config::REGISTRATION_STAGE:
                $handlerName = $this->defineHandler($currentUserStage, $nextStep);
                (new $handlerName($this->userRepository, $this->entityManager))->handle($user, $message, $nextStep);
                break;
            case Config::FILTER_STAGE:
                $handlerName = $this->defineHandler($currentUserStage, $nextStep);
                (new $handlerName($this->userRepository, $this->userFilterRepository, $this->entityManager))->handle($user, $message, $nextStep);
                break;
        }

        // изменение профиля
        if ($currentUserStage === Config::PROFILE_EDIT_STAGE) {
            $handlerName = $this->defineHandler($currentUserStage, $currentUserStep);
            (new $handlerName($this->userRepository, $this->entityManager))->handle($user, $message, $nextStep);
        }

        // изменение фильтров
        if ($currentUserStage === Config::FILTER_EDIT_STAGE) {
            print_r($currentUserStep);
            print_r($currentUserStage);
            print_r('fsdfdsfsf');
            $handlerName = $this->defineHandler($currentUserStage, $currentUserStep);
            (new $handlerName($this->userRepository, $this->userFilterRepository, $this->entityManager))->handle($user, $message, $nextStep);
        }
    }

    /**
     * @throws \Exception
     */
    private function hasUserStage($user): string
    {
        if (!$stage = $user->getStage()) {
            throw new \Exception("User hasn't active stage");
        }
        return $stage;
    }

    /**
     * @param $currentUserStage
     * @param $currentUserStep
     * @return mixed
     * @throws \Exception
     */
    private function defineHandler($currentUserStage, $nextStep): mixed
    {
        if (!isset($this->config::CONFIG[$currentUserStage])) {
            throw new \Exception("The stage doesn't exist!");
        }

        if (!isset($this->config::CONFIG[$currentUserStage][$nextStep])) {
            throw new \Exception("The stage's step doesn't exist!");
        }

        return $this->config::CONFIG[$currentUserStage][$nextStep]['class'];
    }

    public function defineHandlerByUser(User $user)
    {
        return $this->config::CONFIG[$user->getStage()][$user->getStep()]['class'];
    }
}