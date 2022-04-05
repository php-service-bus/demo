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
     * @psalm-readonly
     *
     * @var DriverId
     */
    public $driverId;

    /**
     * Vehicle id
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
