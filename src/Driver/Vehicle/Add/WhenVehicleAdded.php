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

use Amp\Promise;
use App\Driver\Events\VehicleAdded;
use App\Driver\Vehicle\Add\Contract\VehicleAddedToDriver;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 *
 */
final class WhenVehicleAdded
{
    #[EventListener]
    public function on(VehicleAdded $event, ServiceBusContext $context): Promise
    {
        $context->logger()->info('Vehicle successfully added to driver profile', [
            'driverId'  => $event->driverId,
            'vehicleId' => $event->vehicleId
        ]);

        return $context->delivery(
            new VehicleAddedToDriver($context->metadata()->traceId(), $event->driverId, $event->vehicleId)
        );
    }
}
