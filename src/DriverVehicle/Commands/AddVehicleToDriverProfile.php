<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverVehicle\Commands;

use App\Driver\DriverId;
use App\Vehicle\VehicleId;
use Desperado\ServiceBus\Common\Contract\Messages\Command;

/**
 * Add vehicle to driver aggregate
 *
 * internal command
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
     * @param DriverId $driverId
     * @param VehicleId $vehicleId
     *
     * @return self
     */
    public static function create(DriverId $driverId, VehicleId $vehicleId): self
    {
        $self = new self();

        $self->driverId  = $driverId;
        $self->vehicleId = $vehicleId;

        return $self;
    }

    private function __construct()
    {

    }
}
