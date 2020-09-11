<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration;

use App\Customer\Registration\Contract\CustomerRegistrationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Customer registration error
 */
final class WhenCustomerRegistrationFailed
{
    /**
     * @EventListener()
     */
    public function on(CustomerRegistrationFailed $event, ServiceBusContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the client registration process: %s', $event->reason)
            )
        );
    }
}
