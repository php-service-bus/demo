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

namespace App\Customer\Events;

use Desperado\ServiceBus\Common\Contract\Messages\Event;
use App\Customer\CustomerId;
use App\Customer\Data\CustomerFullName;

/**
 * Customer aggregate full name changed
 */
final class FullNameChanged implements Event
{
    /**
     * Customer aggregate id
     *
     * @var CustomerId
     */
    private $id;

    /**
     * Old customer full name data
     *
     * @var CustomerFullName
     */
    private $oldFullName;

    /**
     * New customer full name data
     *
     * @var CustomerFullName
     */
    private $newFullName;

    /**
     * @param CustomerId       $id
     * @param CustomerFullName $oldFullName
     * @param CustomerFullName $newFullName
     *
     * @return self
     */
    public static function create(CustomerId $id, CustomerFullName $oldFullName, CustomerFullName $newFullName): self
    {
        $self = new self();

        $self->id = $id;
        $self->oldFullName = $oldFullName;
        $self->newFullName = $newFullName;

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
    public function oldFullName(): CustomerFullName
    {
        return $this->oldFullName;
    }

    /**
     * @return CustomerFullName
     */
    public function newFullName(): CustomerFullName
    {
        return $this->newFullName;
    }
}
