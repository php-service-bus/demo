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

namespace App\ManageCustomer\Contract\Rename;

use Desperado\ServiceBus\Common\Contract\Messages\Event;
use App\Customer\CustomerId;
use App\Customer\Data\CustomerFullName;

/**
 *
 */
final class CustomerFullNameChanged implements Event
{
    /**
     * Customer ID
     *
     * @var CustomerId
     */
    private $id;

    /**
     * New customer full name
     *
     * @var CustomerFullName
     */
    private $fullName;

    /**
     * @param CustomerId       $id
     * @param CustomerFullName $newFullName
     *
     * @return self
     */
    public static function create(CustomerId $id, CustomerFullName $newFullName): self
    {
        $self = new self();

        $self->id = $id;
        $self->fullName = $newFullName;

        return $self;
    }

    /**
     * @return CustomerId
     */
    public function id(): CustomerId
    {
        return $this->id;
    }

    /**
     * @return CustomerFullName
     */
    public function fullName(): CustomerFullName
    {
        return $this->fullName;
    }
}
