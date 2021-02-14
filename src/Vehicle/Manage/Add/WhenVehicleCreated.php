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

use Amp\Promise;
use App\Vehicle\Events\VehicleCreated;
use App\Vehicle\Manage\Add\Contract\VehicleStored;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 * Vehicle successfully added
 */
final class WhenVehicleCreated
{
    #[EventListener]
    public function onVehicleCreated(VehicleCreated $event, ServiceBusContext $context): Promise
    {
        $context->logger()->info(
            'Vehicle with id "{vehicleId}" successfully added',
            ['vehicleId' => $event->id]
        );

        return $context->delivery(
            new VehicleStored(
                $context->metadata()->traceId(),
                $event->id,
                $event->brand,
                $event->model,
                $event->registrationNumber
            )
        );
    }
}
