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

namespace App\Customer\Contract;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 *
 */
final class CustomerNotExists implements Event
{

    /**
     * Customer aggregate id
     *
     * @var string
     */
    private $customerId;

    /**
     * @param string $customerId
     *
     * @return self
     */
    public static function create(string $customerId): self
    {
        $self = new self();

        $self->customerId = $customerId;

        return $self;
    }

    /**
     * Receive customer aggregate id
     *
     * @return string
     */
    public function customerId(): string
    {
        return $this->customerId;
    }
}
