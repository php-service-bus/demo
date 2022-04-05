<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

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
     * @psalm-readonly
     *
     * @var DriverId
     */
    public $id;

    /**
     * Driver full name
     *
     * @psalm-readonly
     *
     * @var DriverFullName
     */
    public $fullName;

    /**
     * Driver contacts
     *
     * @psalm-readonly
     *
     * @var DriverContacts
     */
    public $contacts;

    public function __construct(DriverId $id, DriverFullName $fullName, DriverContacts $contacts)
    {
        $this->id       = clone $id;
        $this->fullName = clone $fullName;
        $this->contacts = clone $contacts;
    }
}
