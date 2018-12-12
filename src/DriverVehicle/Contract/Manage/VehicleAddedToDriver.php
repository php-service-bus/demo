<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverVehicle\Contract\Manage;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * Vehicle successfully added
 *
 * @api
 * @see AddDriverVehicle
 */
final class VehicleAddedToDriver implements Event
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
     * @var string
     */
    public $driverId;

    /**
     * Vehicle identifier
     *
     * @var string
     */
    public $vehicleId;

    /**
     * @param string $correlationId
     * @param string $driverId
     * @param string $vehicleId
     *
     * @return self
     */
    public static function create(
        string $correlationId,
        string $driverId,
        string $vehicleId
    ): self
    {
        $self = new self();

        $self->correlationId = $correlationId;
        $self->driverId      = $driverId;
        $self->vehicleId     = $vehicleId;

        return $self;
    }

    private function __construct()
    {

    }
}
