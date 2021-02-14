<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Commands;

use App\Driver\DriverId;
use App\Vehicle\VehicleId;

/**
 * Add vehicle to driver aggregate
 *
 * @psalm-immutable
 * @internal
 */
final class AddVehicleToDriver
{
    /**
     * Driver aggregate id
     *
     * @psalm-readonly
     *
     * @var DriverId
     */
    public $driverId;

    /**
     * Vehicle aggregate id
     *
     * @psalm-readonly
     *
     * @var VehicleId
     */
    public $vehicleId;

    public function __construct(DriverId $driverId, VehicleId $vehicleId)
    {
        $this->driverId  = $driverId;
        $this->vehicleId = $vehicleId;
    }
}
