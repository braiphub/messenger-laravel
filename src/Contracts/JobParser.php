<?php

namespace Braip\Messenger\Contracts;

use Braip\Messenger\Events\MessageReceived;
use Illuminate\Contracts\Queue\Job;

interface JobParser
{
    public function parse(Job $job): MessageReceived;
}
