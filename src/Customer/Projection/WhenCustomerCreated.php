<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Projection;

use Amp\Promise;
use App\Customer\Events\CustomerCreated;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;
use ServiceBus\Storage\Common\DatabaseAdapter;
use function Amp\call;
use function ServiceBus\Common\jsonEncode;

/**
 * Create customer projection
 */
final class WhenCustomerCreated
{
    /**
     * @EventListener()
     */
    public function on(CustomerCreated $event, ServiceBusContext $context, DatabaseAdapter $storage): Promise
    {
        return call(
            static function () use ($event, $context, $storage): \Generator
            {
                try
                {
                    yield CustomerReadModel::new($storage, [
                        'id'      => $event->id->toString(),
                        'profile' => jsonEncode([
                            'email'     => $event->contacts->email,
                            'phone'     => $event->contacts->phone,
                            'firstName' => $event->fullName->firstName,
                            'lastName'  => $event->fullName->lastName
                        ])
                    ]);
                }
                catch (\Throwable $throwable)
                {
                    $context->logContextThrowable($throwable);
                }
            }
        );
    }
}
