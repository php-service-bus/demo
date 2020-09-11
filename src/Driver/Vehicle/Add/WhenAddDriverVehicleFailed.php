<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add;

use App\Driver\Vehicle\Add\Contract\AddDriverVehicleFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 *
 */
final class WhenAddDriverVehicleFailed
{
    /**
     * @EventListener()
     */
    public function on(AddDriverVehicleFailed $event, ServiceBusContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the process of saving the vehicle for the driver: %s', $event->reason)
            )
        );
    }
}
