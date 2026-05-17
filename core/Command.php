<?php

namespace Core;

abstract class Command
{
    abstract public function execute(array $args);
    
    protected function log($message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}
