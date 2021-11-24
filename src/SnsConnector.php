<?php

namespace Braip\Messenger;

use Aws\Sns\SnsClient;
use Illuminate\Support\Arr;

class SnsConnector
{
    public function connect(array $config): SnsClient
    {
        $config = $this->getDefaultConfiguration($config);

        if (! empty($config['key']) && ! empty($config['secret'])) {
            $config['credentials'] = Arr::only($config, ['key', 'secret', 'token']);
        }

        return new SnsClient($config);
    }

    protected function getDefaultConfiguration(array $config): array
    {
        return array_merge(
            [
                'version' => 'latest',
                'http' => [
                    'timeout' => 60,
                    'connect_timeout' => 60,
                ],
            ],
            $config
        );
    }
}
