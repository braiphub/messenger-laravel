# Messenger Laravel

## Instalação

Via Composer

```bash
$ composer require braiphub/messenger-laravel
$ php artisan vendor:publish --tag=messenger.config
```

## Como usar

### Enviar Mensagem

Para enviar uma mensagem basta emitir um Evento normal do Laravel com o contrato `Braip\Messenger\Contracts\ShouldMessage`.

```php
use Braip\Messenger\Contracts\ShouldMessage;

class EventoTesteEnviado implements ShouldMessage
{
    public function messageWith(): array
    {
        return [
          // payload que vai ser enviado para os outros sistemas
        ];
    }
}

event(new EventoTesteEnviado());
```

### Receber Mensagem

Colocar no EventServiceProvider o evento desejado com o prefixo `Messenger:`. O listener vai receber um objeto do tipo `Braip\Messenger\Events\MessageReceived` como evento.

```php
//EventServiceProvider.php

protected $listen = [
    'Messenger:{nomeDoEvento}' => [
        // Listeners
    ]
];
```

```php
class ListenerExemplo
{
    public function handle($event)
    {
        $event->payload;
        $event->eventType;
    }
}
```
