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

use App\Customer\Registration\Contract\RegisterCustomerValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Incorrect operation parameters
 */
final class WhenRegisterCustomerValidationFailed
{
    /**
     * @EventListener()
     */
    public function on(RegisterCustomerValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logContextMessage('Incorrect data to create a client', ['violations' => $event->violations]);
    }
}
