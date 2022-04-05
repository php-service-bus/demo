<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver\Registration;

use App\Driver\Events\DriverCreated;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 * Customer successful created
 */
final class WhenDriverCreated
{
    #[EventListener]
    public function on(DriverCreated $event, ServiceBusContext $context): void
    {
        $context->logger()->info(
            'Driver with id "{driverId}" successfully added',
            [
                'driverId' => $event->id->toString()
            ]
        );
    }
}
