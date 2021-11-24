<?php

namespace Braip\Messenger\Tests\Unit;

use Braip\Messenger\Consumer;
use Braip\Messenger\Contracts\JobParser;
use Braip\Messenger\Events\MessageReceived;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Support\Facades\Event;
use Mockery;
use Orchestra\Testbench\TestCase;

class ConsumerTest extends TestCase
{
    /**
     * @test
     */
    public function dispatches_event_on_consume_job()
    {
        Event::fake();

        $parser = Mockery::mock(JobParser::class);
        $job = Mockery::mock(Job::class);

        $parser->shouldReceive('parse')->andReturn(
            new MessageReceived('TestMessageDispatched', [
                'data' => 'test',
            ])
        );

        $job->shouldReceive('getConnectionName')
            ->andReturn('test-connection')
            ->shouldReceive('getQueue')
            ->andReturn('test-queue')
            ->shouldReceive('delete')
            ->andReturnSelf();

        $consumer = new Consumer($parser, 'test-connection', 'test-queue');
        $consumer->consume($job);

        Event::assertDispatched('Messenger:TestMessageDispatched', function ($event, $payload) {
            $message = $payload[0];

            if (! $message instanceof MessageReceived) {
                return false;
            }

            return ['data' => 'test'] == $message->payload;
        });
    }
}
