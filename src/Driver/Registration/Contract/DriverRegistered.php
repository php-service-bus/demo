<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration\Contract;

use App\Driver\DriverId;

/**
 * New driver successful registered
 *
 * @api
 * @see RegisterDriver
 */
final class DriverRegistered
{
    /**
     * Request operation id
     *
     * @var string
     */
    public $correlationId;

    /**
     * Driver identifier
     *
     * @var DriverId
     */
    public $driverId;

    public function __construct(string $correlationId, DriverId $driverId)
    {
        $this->correlationId = $correlationId;
        $this->driverId      = $driverId;
    }
}
