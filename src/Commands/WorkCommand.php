<?php

namespace Braip\Messenger\Commands;

use Illuminate\Queue\Console\WorkCommand as QueueWorkCommand;

class WorkCommand extends QueueWorkCommand
{
    protected $signature = 'messenger:work
                            {connection? : The name of the queue connection to work}
                            {--queue= : The names of the queues to work}
                            {--daemon : Run the worker in daemon mode (Deprecated)}
                            {--once : Only process the next job on the queue}
                            {--stop-when-empty : Stop when the queue is empty}
                            {--delay=0 : The number of seconds to delay failed jobs}
                            {--force : Force the worker to run even in maintenance mode}
                            {--memory=128 : The memory limit in megabytes}
                            {--sleep=3 : Number of seconds to sleep when no job is available}
                            {--timeout=60 : The number of seconds a child process can run}
                            {--tries=1 : Number of times to attempt a job before logging it failed}';

    protected $description = 'Start processing messages as a daemon';

    public function handle()
    {
        if (! $this->argument('connection')) {
            $this->input->setArgument(
                'connection',
                $this->laravel['config']['messenger.consumer.connection']
            );
        }

        parent::handle();
    }

    protected function getQueue($connection)
    {
        if ($this->option('queue')) {
            return parent::getQueue($connection);
        }

        $prefix = $this->laravel['config']->get("queue.connections.{$connection}.prefix");
        $sufix = $this->laravel['config']->get("queue.connections.{$connection}.sufix");

        $queue = trim(
            str_replace(
                [$prefix, $sufix],
                null,
                $this->laravel['config']['messenger.consumer.queue']
            ),
            '/'
        );

        return $queue;
    }

    protected function listenForEvents()
    {
        // don't
    }
}
