<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer;

use App\Customer\Data\CustomerContacts;
use App\Customer\Data\CustomerFullName;
use App\Customer\Events\CustomerCreated;
use ServiceBus\EventSourcing\Aggregate;

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
     * @noinspection PhpDocMissingThrowsInspection
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
        $self = new self(CustomerId::new());

        /** @noinspection PhpUnhandledExceptionInspection */
        $self->raise(CustomerCreated::create((string) $self->id(), $phone, $email, $firstName, $lastName));

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
        $this->contacts = CustomerContacts::create($event->email, $event->phone);
        $this->fullName = CustomerFullName::create($event->firstName, $event->lastName);
    }
}