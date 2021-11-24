<?php

return [
    'prefix' => env('MESSENGER_PREFIX', 'BraipMessenger'),

    'consumer' => [
        'connection' => env('MESSENGER_CONSUMER_CONNECTION', 'sqs'),
        'queue' => env('MESSENGER_CONSUMER_QUEUE', 'Messenger.fifo'),
    ],

    'publisher' => [
        'service' => env('MESSENGER_PUBLISHER_SERVICE', 'sns'),
        'topic' => env('MESSENGER_PUBLISHER_TOPIC_ARN', ''),
    ],
];
