<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Events;

use App\Driver\DriverContacts;
use App\Driver\DriverFullName;
use App\Driver\DriverId;

/**
 * Driver aggregate created
 *
 * @internal
 *
 * @psalm-immutable
 */
final class DriverCreated
{
    /**
     * Driver aggregate id
     *
     * @var DriverId
     */
    public $id;

    /**
     * Driver full name
     *
     * @var DriverFullName
     */
    public $fullName;

    /**
     * Driver contacts
     *
     * @var DriverContacts
     */
    public $contacts;

    public function __construct(DriverId $id, DriverFullName $fullName, DriverContacts $contacts)
    {
        $this->id       = $id;
        $this->fullName = clone $fullName;
        $this->contacts = clone $contacts;
    }
}
