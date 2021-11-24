<?php

namespace Braip\Messenger\Contracts;

interface Publisher
{
    public function publish(string $eventType, array $data): void;
}
