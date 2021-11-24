<?php

namespace Braip\Messenger;

use Braip\Messenger\Contracts\JobParser;
use Braip\Messenger\Events\MessageReceived;
use Illuminate\Contracts\Queue\Job;

class SqsJobParser implements JobParser
{
    public function parse(Job $job): MessageReceived
    {
        $payload = $job->payload();

        $messagePayload = json_decode($payload['Message'], true);
        $eventType = $payload['MessageAttributes']['eventType']['Value'];

        return new MessageReceived($eventType, $messagePayload);
    }
}
