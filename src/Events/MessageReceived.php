<?php

namespace Braip\Messenger\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MessageReceived
{
    use Dispatchable;

    public $eventType;

    public $payload;

    public function __construct(string $eventType, array $payload)
    {
        $this->eventType = $eventType;
        $this->payload = $payload;
    }

    public function __toString(): string
    {
        return sprintf('Messenger:%s', $this->eventType);
    }
}
