<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverVehicle\Commands;

use App\Driver\DriverId;
use App\Vehicle\VehicleId;
use ServiceBus\Common\Messages\Command;

/**
 * Add vehicle to driver aggregate
 *
 * internal command
 *
 * @property-read DriverId  $driverId
 * @property-read VehicleId $vehicleId
 */
final class AddVehicleToDriverProfile implements Command
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

    /**
     * @param DriverId  $driverId
     * @param VehicleId $vehicleId
     *
     * @return self
     */
    public static function create(DriverId $driverId, VehicleId $vehicleId): self
    {
        return new self($driverId, $vehicleId);
    }

    /**
     * @param DriverId  $driverId
     * @param VehicleId $vehicleId
     */
    private function __construct(DriverId $driverId, VehicleId $vehicleId)
    {
        $this->driverId  = $driverId;
        $this->vehicleId = $vehicleId;
    }
}
