<?php

namespace Braip\Messenger\Listeners;

use Braip\Messenger\Contracts\Publisher;
use Braip\Messenger\Contracts\ShouldMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishMessage implements ShouldQueue
{
    /** @var string */
    public $queue = 'messenger';

    /** @var \Braip\Messenger\Contracts\Publisher */
    protected $publisher;

    public function __construct(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function handle(ShouldMessage $event)
    {
        $eventType = class_basename($event);

        $this->publisher->publish($eventType, $event->messageWith());
    }
}
