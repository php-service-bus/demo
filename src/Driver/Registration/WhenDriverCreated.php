<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration;

use Amp\Promise;
use App\Driver\Events\DriverCreated;
use App\Driver\Registration\Contract\DriverRegistered;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;
use function Amp\call;

/**
 * Customer successful created
 */
final class WhenDriverCreated
{
    #[EventListener]
    public function on(DriverCreated $event, ServiceBusContext $context): Promise
    {
        return call(
            static function () use ($event, $context): \Generator
            {
                try
                {
                    yield $context->delivery(
                        new DriverRegistered($context->metadata()->traceId(), $event->id)
                    );

                    $context->logger()->info(
                        'Driver with id "{driverId}" successfully added',
                        ['driverId' => $event->id->toString()]
                    );
                }
                catch (\Throwable $throwable)
                {
                    $context->logger()->throwable($throwable);
                }
            }
        );
    }
}
