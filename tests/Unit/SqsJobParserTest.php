<?php

namespace Braip\Messenger\Tests\Unit;

use Aws\Sqs\SqsClient;
use Braip\Messenger\SqsJobParser;
use Illuminate\Queue\Jobs\SqsJob;
use Mockery;
use Orchestra\Testbench\TestCase;

class SqsJobParserTest extends TestCase
{
    /**
     * @test
     */
    public function receives_sqs_job()
    {
        $sqsJob = [
            'MessageId' => 'test-message-id',
            'ReceiptHandle' => 'test-receipt-handle',
            'Body' => json_encode([
                'Message' => json_encode([
                    'id' => 'fdaa80a1-ddd1-4f1e-871e-d8a831b2721f',
                    'data' => 'Test Message',
                ]),
                'MessageAttributes' => [
                    'eventType' => [
                        'Type' => 'String',
                        'Value' => 'TestMessageDispatched',
                    ],
                ],
            ]),
            'Attributes' => [
                'ApproximateReceiveCount' => 1,
            ],
        ];

        $sqsClient = Mockery::mock(SqsClient::class);

        $job = new SqsJob($this->app, $sqsClient, $sqsJob, 'test-connection', 'test-queue');

        $receiver = new SqsJobParser();
        $messageReceived = $receiver->parse($job);

        $this->assertEquals('Messenger:TestMessageDispatched', (string) $messageReceived);
        $this->assertEquals(
            [
                'id' => 'fdaa80a1-ddd1-4f1e-871e-d8a831b2721f',
                'data' => 'Test Message',
            ],
            $messageReceived->payload
        );
    }
}
