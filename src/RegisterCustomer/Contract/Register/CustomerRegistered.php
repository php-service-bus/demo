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

namespace ServiceBusDemo\RegisterCustomer\Contract\Register;

use Desperado\ServiceBus\Common\Contract\Messages\Event;
use ServiceBusDemo\Customer\CustomerId;

/**
 *
 */
final class CustomerRegistered implements Event
{

    /**
     * CustomerId
     *
     * @var string
     */
    private $customerId;

    /**
     * Email address
     *
     * @var string
     */
    private $email;

    /**
     * @param CustomerId $customerId
     * @param string     $email
     *
     * @return self
     */
    public static function create(CustomerId $customerId, string $email): self
    {
        $self = new self();

        $self->customerId = (string) $customerId;
        $self->email = $email;

        return $self;
    }

    private function __construct()
    {

    }
}
