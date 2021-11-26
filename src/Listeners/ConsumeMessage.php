<?php

namespace Braip\Messenger\Listeners;

use Braip\Messenger\Contracts\Consumer;
use Illuminate\Queue\Events\JobProcessing;

class ConsumeMessage
{
    /** @var \Braip\Messenger\Contracts\Consumer */
    protected $consumer;

    public function __construct(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function handle(JobProcessing $event)
    {
        $this->consumer->consume($event->job);

        if ($event->job->isDeleted()) {
            return false;
        }
    }
}
