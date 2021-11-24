<?php

namespace Braip\Messenger;

use Braip\Messenger\Contracts\JobParser;
use Illuminate\Contracts\Queue\Job;

class Consumer implements Contracts\Consumer
{
    /** @var \Braip\Messenger\Contracts\JobParser */
    protected $parser;

    /** @var string */
    protected $connection;

    /** @var string */
    protected $queue;

    public function __construct(JobParser $parser, string $connection, string $queue)
    {
        $this->parser = $parser;
        $this->connection = $connection;
        $this->queue = $queue;
    }

    public function consume(Job $job): void
    {
        if ($this->shouldIgnore($job)) {
            return;
        }

        $message = $this->parser->parse($job);

        event((string) $message, [$message]);

        $job->delete();
    }

    protected function shouldConsume(Job $job): bool
    {
        return $this->connection == $job->getConnectionName() && $this->queue == $job->getQueue();
    }

    protected function shouldIgnore(Job $job): bool
    {
        return ! $this->shouldConsume($job);
    }
}
