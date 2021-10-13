<?php

interface MessageHandlerStrategyInterface
{
    public function process(array $message);
}