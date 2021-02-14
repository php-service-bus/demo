<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration\Contract;

use App\Driver\DriverId;

/**
 * New driver successful registered
 *
 * @psalm-immutable
 *
 * @api
 * @see RegisterDriver
 */
final class DriverRegistered
{
    /**
     * Request operation id
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $correlationId;

    /**
     * Driver identifier
     *
     * @psalm-readonly
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
