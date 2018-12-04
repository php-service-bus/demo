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

namespace App\Customer;

use Desperado\ServiceBus\EventSourcing\Aggregate;
use App\Customer\Data\CustomerContacts;
use App\Customer\Data\CustomerCredentials;
use App\Customer\Data\CustomerFullName;
use App\Customer\Event\CustomerAggregateCreated;
use App\Customer\Events\FullNameChanged;

/**
 *
 * @method CustomerId id
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
     * @param CustomerFullName $fullName
     * @param string           $clearPassword
     * @param CustomerContacts $contacts
     *
     * @return self
     */
    public static function create(CustomerFullName $fullName, string $clearPassword, CustomerContacts $contacts): self
    {
        $id = CustomerId::new();
        $credentials = CustomerCredentials::encodeClearPassword($clearPassword);

        $self = new self($id);

        $self->raise(
            CustomerAggregateCreated::create($id, $fullName, $credentials, $contacts)
        );

        return $self;
    }

    /**
     * Change customer full name
     *
     * @param CustomerFullName $newFullName
     *
     * @return void
     */
    public function rename(CustomerFullName $newFullName): void
    {
        $this->raise(
            FullNameChanged::create(
                $this->id(),
                $this->fullName,
                $newFullName
            )
        );
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param FullNameChanged $event
     *
     * @return void
     */
    private function onFullNameChanged(FullNameChanged $event): void
    {
        $this->fullName = $event->newFullName();
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
        $this->fullName = $event->fullName();
        $this->contacts = $event->contacts();
        $this->credentials = $event->credentials();
    }
}
