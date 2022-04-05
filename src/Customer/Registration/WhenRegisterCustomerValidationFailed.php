<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Customer\Registration;

use App\Customer\Registration\Contract\RegisterCustomerValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 * Incorrect operation parameters
 */
final class WhenRegisterCustomerValidationFailed
{
    #[EventListener]
    public function on(RegisterCustomerValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logger()->info(
            'Incorrect data to create a customer `{customerId}`',
            [
                'violations' => $event->violations->violations,
                'customerId' => $event->customerId->toString()
            ]
        );
    }
}
