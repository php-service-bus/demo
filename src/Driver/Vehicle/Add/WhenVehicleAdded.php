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

use Amp\Promise;
use App\Driver\Events\VehicleAdded;
use App\Driver\Vehicle\Add\Contract\VehicleAddedToDriver;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 *
 */
final class WhenVehicleAdded
{
    /**
     * @EventListener()
     */
    public function on(VehicleAdded $event, ServiceBusContext $context): Promise
    {
        $context->logContextMessage('Vehicle successfully added to driver profile', [
            'driverId'  => $event->driverId,
            'vehicleId' => $event->vehicleId
        ]);

        return $context->delivery(
            new VehicleAddedToDriver($context->traceId(), $event->driverId, $event->vehicleId)
        );
    }
}
