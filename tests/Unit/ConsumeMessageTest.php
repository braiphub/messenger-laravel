<?php

namespace Braip\Messenger\Tests\Unit;

use Braip\Messenger\Contracts\Consumer;
use Braip\Messenger\Listeners\ConsumeMessage;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessing;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class ConsumeMessageTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function consumes_job_with_consumer()
    {
        $consumer = Mockery::mock(Consumer::class);
        $job = Mockery::mock(Job::class);

        $event = new JobProcessing('test-connection', $job);

        $consumer
            ->shouldReceive('consume')
            ->withArgs([$job])
            ->once();

        $listener = new ConsumeMessage($consumer);
        $listener->handle($event);
    }
}
