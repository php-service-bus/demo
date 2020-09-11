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

use App\Driver\DriverId;
use App\Vehicle\VehicleId;

/**
 * Vehicle successfully added to driver profile
 *
 * @internal
 */
final class VehicleAdded
{
    /**
     * Driver id
     *
     * @var DriverId
     */
    public $driverId;

    /**
     * Vehicle id
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
