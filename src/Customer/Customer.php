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

use Desperado\ServiceBus\EventSourcing\Aggregate;
use ServiceBusDemo\Customer\Data\CustomerContacts;
use ServiceBusDemo\Customer\Data\CustomerCredentials;
use ServiceBusDemo\Customer\Data\CustomerFullName;
use ServiceBusDemo\Customer\Event\CustomerCreated;

/**
 *
 */
final class Customer extends Aggregate
{
    /**
     * @var CustomerFullName
     */
    private $fullName;

    /**
     * @var CustomerCredentials
     */
    private $credentials;

    /**
     * @var CustomerContacts
     */
    private $contacts;

    /**
     * @param CustomerId          $id
     * @param CustomerFullName    $fullName
     * @param CustomerCredentials $credentials
     * @param CustomerContacts    $contacts
     *
     * @return self
     */
    public static function create(
        CustomerId $id,
        CustomerFullName $fullName,
        CustomerCredentials $credentials,
        CustomerContacts $contacts
    ): self
    {
        $self = new self($id);
        $self->raise(CustomerCreated::create($id, $fullName, $credentials, $contacts));

        return $self;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param CustomerCreated $event
     *
     * @return void
     */
    private function onCustomerCreated(CustomerCreated $event): void
    {
        $this->fullName    = $event->fullName();
        $this->contacts    = $event->contacts();
        $this->credentials = $event->credentials();
    }
}
