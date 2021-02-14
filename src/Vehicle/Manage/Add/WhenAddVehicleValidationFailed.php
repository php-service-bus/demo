<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Add;

use App\Vehicle\Manage\Add\Contract\AddVehicleValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 * Incorrect data to create a vehicle
 */
final class WhenAddVehicleValidationFailed
{
    /**
     * @EventListener()
     */
    #[EventListener]
    public function on(AddVehicleValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logger()->info(
            'Incorrect data to create a vehicle',
            ['violations' => $event->violations->violations]
        );
    }
}
