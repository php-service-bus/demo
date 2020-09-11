<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
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
 * @internal
 */
final class AddVehicleToDriver
{
    /**
     * Driver aggregate id
     *
     * @var DriverId
     */
    public $driverId;

    /**
     * Vehicle aggregate id
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
