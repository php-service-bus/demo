<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add;

use App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 *
 */
final class WhenAddDriverVehicleValidationFailed
{
    #[EventListener]
    public function on(AddDriverVehicleValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logger()->info(
            'Validation error in the process of saving the vehicle for the driver',
            [ 'violations' => $event->violations->violations]
        );
    }
}
