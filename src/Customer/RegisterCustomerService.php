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
use Desperado\ServiceBus\EventSourcingProvider;
use Desperado\ServiceBus\Services\Annotations\CommandHandler;
use ServiceBusDemo\Customer\Command\RegisterCustomer;

/**
 *
 */
final class RegisterCustomerService
{
    /**
     * @CommandHandler(
     *     validate=true,
     *     groups={"registration"}
     * )
     *
     * @param RegisterCustomer      $command
     * @param KernelContext         $context
     * @param EventSourcingProvider $eventSourcingProvider
     *
     * @return \Generator<null>
     */
    public function register(
        RegisterCustomer $command,
        KernelContext $context,
        EventSourcingProvider $eventSourcingProvider
    ): \Generator
    {

    }
}
