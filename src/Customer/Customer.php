<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer;

use App\Customer\Data\CustomerContacts;
use App\Customer\Data\CustomerFullName;
use App\Customer\Events\CustomerAggregateCreated;
use Desperado\ServiceBus\EventSourcing\Aggregate;

/**
 * Customer aggregate
 */
final class Customer extends Aggregate
{
    /**
     * Contact information
     *
     * @var CustomerContacts
     */
    private $contacts;

    /**
     * Customer full name data
     *
     * @var CustomerFullName
     */
    private $fullName;

    /**
     * Create new customer aggregate
     *
     * @param string $phone
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     *
     * @return self
     */
    public static function register(string $phone, string $email, string $firstName, string $lastName): self
    {
        $self = new self(CustomerId::new(__CLASS__));

        $self->raise(CustomerAggregateCreated::create((string) $self->id(), $phone, $email, $firstName, $lastName));

        return $self;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param CustomerAggregateCreated $event
     *
     * @return void
     */
    private function onCustomerAggregateCreated(CustomerAggregateCreated $event): void
    {
        $this->contacts = new CustomerContacts($event->email, $event->phone);
        $this->fullName = new CustomerFullName($event->firstName, $event->lastName);
    }
}