<?php

namespace Braip\Messenger;

use Aws\Sns\SnsClient;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;

class Publisher implements Contracts\Publisher
{
    /** @var SnsClient */
    protected $sns;

    /** @var UuidFactory */
    protected $uuidFactory;

    /** @var string */
    protected $eventPrefix;

    /** @var string */
    protected $topic;

    public function __construct(
        SnsClient $sns,
        UuidFactoryInterface $uuidFactory,
        string $eventPrefix,
        string $topic
    ) {
        $this->sns = $sns;
        $this->uuidFactory = $uuidFactory;
        $this->eventPrefix = $eventPrefix;
        $this->topic = $topic;
    }

    public function publish(string $eventType, array $data): void
    {
        $this->sns->publish([
            'Message' => json_encode($data),
            'TopicArn' => $this->topic,
            'MessageGroupId' => $this->eventPrefix.':'.$eventType,
            'MessageDeduplicationId' => (string) $this->uuidFactory->uuid4(),
            'MessageAttributes' => [
                'eventType' => [
                    'DataType' => 'String',
                    'StringValue' => $this->eventPrefix.':'.$eventType,
                ],
            ],
        ]);
    }
}
