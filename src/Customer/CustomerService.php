<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) demo
 * Supports Saga pattern and Event Sourcing
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBusDemo\Customer;

use Desperado\ServiceBus\Application\KernelContext;
use Desperado\ServiceBus\Services\Annotations\EventListener;
use ServiceBusDemo\Customer\Contract\CustomerNotExists;

/**
 *
 */
final class CustomerService
{
    /**
     * @EventListener()
     *
     * @param CustomerNotExists $event
     * @param KernelContext     $context
     *
     * @return void
     */
    public function whenCustomerNotExists(CustomerNotExists $event, KernelContext $context): void
    {
        $context->logContextMessage(
            'Customer with id "{customerId}" not found', ['customerId' => $event->customerId()]
        );
    }
}
