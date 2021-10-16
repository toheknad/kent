<?php
namespace App\Service\Telegram\Strategy;

interface MessageHandlerStrategyInterface
{
    public function process(array $message);
}