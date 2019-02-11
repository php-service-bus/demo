<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Projections;

use App\Customer\Events\CustomerCreated;
use ServiceBus\Context\KernelContext;
use ServiceBus\Services\Annotations\EventListener;
use ServiceBus\Storage\Common\DatabaseAdapter;

/**
 *
 */
final class CustomerProjectionsService
{
    /**
     * @EventListener()
     *
     * @param CustomerCreated $event
     * @param KernelContext   $context
     * @param DatabaseAdapter $storageAdapter
     *
     * @return \Generator
     *
     * @throws \Throwable
     */
    public function whenCustomerCreated(
        CustomerCreated $event,
        /** @noinspection PhpUnusedParameterInspection */
        KernelContext $context,
        DatabaseAdapter $storageAdapter
    ): \Generator
    {
        yield CustomerReadModel::new($storageAdapter, [
            'id'      => $event->id,
            'profile' => \json_encode([
                'email'     => $event->email,
                'phone'     => $event->phone,
                'firstName' => $event->firstName,
                'lastName'  => $event->lastName
            ])
        ]);
    }
}
