<?php

declare(strict_types = 1);

use function Amp\Promise\wait;
use function ServiceBus\Common\uuid;
use ServiceBus\MessageSerializer\MessageEncoder;
use ServiceBus\MessageSerializer\Symfony\SymfonyMessageSerializer;
use ServiceBus\Transport\Amqp\AmqpConnectionConfiguration;
use ServiceBus\Transport\Amqp\AmqpTransportLevelDestination;
use ServiceBus\Transport\Common\Package\OutboundPackage;
use ServiceBus\Transport\Common\Transport;
use ServiceBus\Transport\Amqp\PhpInnacle\PhpInnacleTransport;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Tools message publisher.
 *
 * For tests/debug only
 */
final class ToolsPublisher
{
    /**
     * @var Transport|null
     */
    private $transport;

    /**
     * @var MessageEncoder
     */
    private $encoder;

    /**
     * @throws \Symfony\Component\Dotenv\Exception\FormatException
     * @throws \Symfony\Component\Dotenv\Exception\PathException
     */
    public function __construct(string $envPath)
    {
        (new Dotenv())->usePutenv(true)->load($envPath);

        $this->encoder = new SymfonyMessageSerializer();
    }

    /**
     * Send message to queue.
     */
    public function sendMessage(object $message, string $traceId = null, ?string $topic = null, ?string $routingKey = null): void
    {
        $topic      = (string) ($topic ?? \getenv('SENDER_DESTINATION_TOPIC'));
        $routingKey = (string) ($routingKey ?? \getenv('SENDER_DESTINATION_TOPIC_ROUTING_KEY'));

        /** @noinspection PhpUnhandledExceptionInspection */
        wait(
            $this->transport()->send(
                new OutboundPackage(
                    $this->encoder->encode($message),
                    [Transport::SERVICE_BUS_TRACE_HEADER => $traceId ?? uuid()],
                    new AmqpTransportLevelDestination($topic, $routingKey),
                    uuid()
                )
            )
        );
    }

    private function transport(): Transport
    {
        if (null === $this->transport)
        {
            $this->transport = new PhpInnacleTransport(
                new AmqpConnectionConfiguration(\getenv('TRANSPORT_CONNECTION_DSN'))
            );

            /** @noinspection PhpUnhandledExceptionInspection */
            wait($this->transport->connect());
        }

        return $this->transport;
    }
}
