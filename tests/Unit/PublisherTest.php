<?php

namespace Braip\Messenger\Tests\Unit;

use Aws\Sns\SnsClient;
use Braip\Messenger\Publisher;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;

class PublisherTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function sends_message_throught_sns_client()
    {
        $snsClient = Mockery::mock(SnsClient::class);
        $uuidFactory = Mockery::mock(UuidFactoryInterface::class);
        $uuid = Mockery::mock(UuidInterface::class);

        $uuid->shouldReceive('__toString')->andReturn('91c8cc66-b94d-4e69-b4d9-aa8c7952c352');
        $uuidFactory->shouldReceive('uuid4')->andReturn($uuid);

        $snsClient
            ->shouldReceive('publish')
            ->once()
            ->with([
                'Message' => json_encode(['message' => 'Test Message']),
                'TopicArn' => 'TestMessenger.fifo',
                'MessageGroupId' => 'Test:MessageSent',
                'MessageDeduplicationId' => '91c8cc66-b94d-4e69-b4d9-aa8c7952c352',
                'MessageAttributes' => [
                    'eventType' => [
                        'DataType' => 'String',
                        'StringValue' => 'Test:MessageSent',
                    ],
                ],
            ]);

        $publisher = new Publisher($snsClient, $uuidFactory, 'Test', 'TestMessenger.fifo');
        $publisher->publish('MessageSent', [
            'message' => 'Test Message',
        ]);
    }
}
