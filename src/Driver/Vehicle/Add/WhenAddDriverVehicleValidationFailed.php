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

use App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 *
 */
final class WhenAddDriverVehicleValidationFailed
{
    /**
     * @EventListener()
     */
    public function on(AddDriverVehicleValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logContextMessage(
            'Validation error in the process of saving the vehicle for the driver',
            [
                'violations' => $event->violations
            ]
        );
    }
}
