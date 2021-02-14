<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add\Contract;

use App\Driver\DriverId;
use App\Vehicle\VehicleId;

/**
 * Vehicle successfully added
 *
 * @api
 * @see AddDriverVehicle
 *
 * @psalm-immutable
 */
final class VehicleAddedToDriver
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

    /**
     * Vehicle identifier
     *
     * @psalm-readonly
     *
     * @var VehicleId
     */
    public $vehicleId;

    public function __construct(string $correlationId, DriverId $driverId, VehicleId $vehicleId)
    {
        $this->correlationId = $correlationId;
        $this->driverId      = clone $driverId;
        $this->vehicleId     = clone $vehicleId;
    }
}
