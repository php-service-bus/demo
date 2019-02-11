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
use ServiceBus\Common\Messages\Event;

/**
 * Vehicle successfully added to driver profile
 *
 * internal event
 */
final class VehicleAdded implements Event
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

    /**
     * @param DriverId  $driverId
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
