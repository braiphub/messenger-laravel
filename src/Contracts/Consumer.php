<?php

namespace Braip\Messenger\Contracts;

use Illuminate\Contracts\Queue\Job;

interface Consumer
{
    public function consume(Job $job): void;
}
