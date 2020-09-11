<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration;

use App\Driver\Registration\Contract\DriverRegistrationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Driver registration error
 */
final class WhenDriverRegistrationFailed
{
    /**
     * @EventListener()
     */
    public function on(DriverRegistrationFailed $event, ServiceBusContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the driver registration process: %s', $event->reason)
            )
        );
    }
}
