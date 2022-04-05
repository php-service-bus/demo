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

use App\Customer\Events\CustomerCreated;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 * Customer registered successfully
 */
final class WhenCustomerCreated
{
    #[EventListener]
    public function on(CustomerCreated $event, ServiceBusContext $context): void
    {
        $context->logger()->info(
            'Customer with id "{customerId}" successfully added',
            ['customerId' => $event->id->toString()]
        );
    }
}
