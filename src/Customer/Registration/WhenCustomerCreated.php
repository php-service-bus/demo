<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration;

use Amp\Promise;
use App\Customer\Events\CustomerCreated;
use App\Customer\Registration\Contract\CustomerRegistered;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;
use function Amp\call;

/**
 * Customer registered successfully
 */
final class WhenCustomerCreated
{
    #[EventListener]
    public function on(CustomerCreated $event, ServiceBusContext $context): Promise
    {
        return call(
            static function () use ($event, $context): \Generator
            {
                try
                {
                    yield $context->delivery(
                        new CustomerRegistered($context->metadata()->traceId(), $event->id)
                    );

                    $context->logger()->info(
                        'Customer with id "{customerId}" successfully added',
                        ['customerId' => $event->id->toString()]
                    );
                }
                catch (\Throwable $throwable)
                {
                    $context->logger()->throwable($throwable);
                }
            }
        );
    }
}
