<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Add;

use App\Vehicle\Manage\Add\Contract\AddVehicleFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Error in the process of saving the vehicle
 */
final class WhenAddVehicleFailed
{
    /**
     * @EventListener()
     */
    public function on(AddVehicleFailed $event, ServiceBusContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the process of saving the vehicle: %s', $event->reason)
            )
        );
    }
}
