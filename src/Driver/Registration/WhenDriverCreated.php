<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration;

use Amp\Promise;
use App\Driver\Events\DriverCreated;
use App\Driver\Registration\Contract\DriverRegistered;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;
use function Amp\call;

/**
 * Customer successful created
 */
final class WhenDriverCreated
{
    /**
     * @EventListener()
     */
    public function on(DriverCreated $event, ServiceBusContext $context): Promise
    {
        return call(
            static function () use ($event, $context): \Generator
            {
                try
                {
                    yield $context->delivery(
                        new DriverRegistered($context->traceId(), $event->id)
                    );

                    $context->logContextMessage(
                        'Driver with id "{driverId}" successfully added',
                        ['driverId' => $event->id->toString()]
                    );
                }
                catch (\Throwable $throwable)
                {
                    $context->logContextThrowable($throwable);
                }
            }
        );
    }
}
