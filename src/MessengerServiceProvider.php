<?php

namespace Braip\Messenger;

use Braip\Messenger\Commands\WorkCommand;
use Braip\Messenger\Listeners\ConsumeMessage;
use Braip\Messenger\Listeners\PublishMessage;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\Uuid;

class MessengerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        Event::listen(JobProcessing::class, ConsumeMessage::class);
        Event::listen(Contracts\ShouldMessage::class, PublishMessage::class);

        $this->app->bind(Contracts\Consumer::class, function ($app) {
            $config = $app['config']['messenger.consumer'];

            return new Consumer(new SqsJobParser(), $config['connection'], $config['queue']);
        });

        $this->app->bind(Contracts\Publisher::class, function ($app) {
            $config = $app['config'];
            $service = $config['services.'.$config['messenger.publisher.service']];

            $connector = new SnsConnector();
            $sns = $connector->connect($service);

            return new Publisher(
                $sns,
                Uuid::getFactory(),
                $config['messenger.prefix'],
                $config['messenger.publisher.topic']
            );
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/messenger.php', 'messenger');
        $this->mergeConfigFrom(__DIR__.'/../../config/services.php', 'services');
    }

    protected function bootForConsole(): void
    {
        $this->publishes(
            [
                __DIR__.'/../../config/messenger.php' => config_path('messenger.php'),
            ],
            'messenger.config'
        );

        $this->commands([WorkCommand::class]);
    }
}
