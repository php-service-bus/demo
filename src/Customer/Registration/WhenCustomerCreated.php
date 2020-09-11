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

use Amp\Promise;
use App\Customer\Events\CustomerCreated;
use App\Customer\Registration\Contract\CustomerRegistered;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;
use function Amp\call;

/**
 * Customer registered successfully
 */
final class WhenCustomerCreated
{
    /**
     * @EventListener()
     */
    public function on(CustomerCreated $event, ServiceBusContext $context): Promise
    {
        return call(
            static function () use ($event, $context): \Generator
            {
                try
                {
                    yield $context->delivery(
                        new CustomerRegistered($context->traceId(), $event->id)
                    );

                    $context->logContextMessage(
                        'Customer with id "{customerId}" successfully added',
                        ['customerId' => $event->id->toString()]
                    );
                }
                catch (\Throwable $throwable)
                {
                    $context->logContextThrowable($throwable);
                }
            }
        );
    }
}
