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

namespace App\Customer\Event;

use Desperado\ServiceBus\Common\Contract\Messages\Event;
use App\Customer\CustomerId;
use App\Customer\Data\CustomerContacts;
use App\Customer\Data\CustomerCredentials;
use App\Customer\Data\CustomerFullName;

/**
 * Customer aggregate created
 */
final class CustomerAggregateCreated implements Event
{
    /**
     * Customer aggregate id
     *
     * @var CustomerId
     */
    private $id;

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
        $self = new self();

        $self->id          = $id;
        $self->fullName    = $fullName;
        $self->credentials = $credentials;
        $self->contacts    = $contacts;

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

    /**
     * @return CustomerCredentials
     */
    public function credentials(): CustomerCredentials
    {
        return $this->credentials;
    }

    /**
     * @return CustomerContacts
     */
    public function contacts(): CustomerContacts
    {
        return $this->contacts;
    }

    private function __construct()
    {

    }
}
