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

use App\Vehicle\Manage\Add\Contract\AddVehicleValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Incorrect data to create a vehicle
 */
final class WhenAddVehicleValidationFailed
{
    /**
     * @EventListener()
     */
    public function on(AddVehicleValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logContextMessage('Incorrect data to create a vehicle', ['violations' => $event->violations]);
    }
}
