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

use Amp\Promise;
use App\Vehicle\Events\VehicleCreated;
use App\Vehicle\Manage\Add\Contract\VehicleStored;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Vehicle successfully added
 */
final class WhenVehicleCreated
{
    /**
     * @EventListener()
     */
    public function onVehicleCreated(VehicleCreated $event, ServiceBusContext $context): Promise
    {
        $context->logContextMessage(
            'Vehicle with id "{vehicleId}" successfully added',
            ['vehicleId' => $event->id]
        );

        return $context->delivery(
            new VehicleStored(
                $context->traceId(),
                $event->id,
                $event->brand,
                $event->model,
                $event->registrationNumber
            )
        );
    }
}
