<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Events;

use App\Customer\CustomerContacts;
use App\Customer\CustomerFullName;
use App\Customer\CustomerId;

/**
 * Customer aggregate created
 *
 * @internal
 *
 * @psalm-immutable
 */
final class CustomerCreated
{
    /**
     * Customer aggregate id
     *
     * @var CustomerId
     */
    public $id;

    /**
     * Customer full name
     *
     * @var CustomerFullName
     */
    public $fullName;

    /**
     * Customer contacts
     *
     * @var CustomerContacts
     */
    public $contacts;

    public function __construct(CustomerId $id, CustomerFullName $fullName, CustomerContacts $contacts)
    {
        $this->id       = $id;
        $this->fullName = clone $fullName;
        $this->contacts = clone $contacts;
    }
}
