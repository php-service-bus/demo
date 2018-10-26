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

namespace ServiceBusDemo\Customer\Event;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * @see RegisterCustomer
 */
final class CustomerAlreadyRegistered implements Event
{
    /**
     * Trace ID
     *
     * @var string
     */
    private $operationId;

    /**
     * Email address
     *
     * @var string
     */
    private $email;

    /**
     * @param string $operationId
     * @param string $email
     *
     * @return self
     */
    public static function create(string $operationId, string $email): self
    {
        $self = new self();

        $self->operationId = $operationId;
        $self->email       = $email;

        return $self;
    }

    private function __construct()
    {

    }
}
