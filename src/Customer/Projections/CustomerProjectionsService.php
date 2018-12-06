<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Projections;

use Amp\Promise;
use App\Customer\Events\CustomerAggregateCreated;
use Desperado\ServiceBus\Application\KernelContext;
use function Desperado\ServiceBus\Infrastructure\Storage\SQL\insertQuery;
use Desperado\ServiceBus\Infrastructure\Storage\StorageAdapter;
use Desperado\ServiceBus\Services\Annotations\EventListener;

/**
 *
 */
final class CustomerProjectionsService
{
    /**
     * @EventListener()
     *
     * @param CustomerAggregateCreated $event
     * @param KernelContext            $context
     * @param StorageAdapter           $storageAdapter
     *
     * @return Promise
     */
    public function whenCustomerAggregateCreated(
        CustomerAggregateCreated $event,
        /** @noinspection PhpUnusedParameterInspection */
        KernelContext $context,
        StorageAdapter $storageAdapter
    ): Promise
    {
        $insertQueryBuilder = insertQuery('customer', [
            'id'      => $event->id,
            'profile' => \json_encode([
                'email'     => $event->email,
                'phone'     => $event->phone,
                'firstName' => $event->firstName,
                'lastName'  => $event->lastName
            ])
        ]);

        $insertQuery = $insertQueryBuilder->compile();

        return $storageAdapter->execute($insertQuery->sql(), $insertQuery->params());
    }
}
