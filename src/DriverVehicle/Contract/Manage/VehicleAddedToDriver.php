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

use ServiceBus\Common\Messages\Event;

/**
 * Vehicle successfully added
 *
 * @api
 * @see AddDriverVehicle
 *
 * @property-read string $correlationId
 * @property-read string $driverId
 * @property-read string $vehicleId
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
    public static function create(string $correlationId, string $driverId, string $vehicleId): self
    {
        return new self($correlationId, $driverId, $vehicleId);
    }

    /**
     * @param string $correlationId
     * @param string $driverId
     * @param string $vehicleId
     */
    private function __construct(string $correlationId, string $driverId, string $vehicleId)
    {
        $this->correlationId = $correlationId;
        $this->driverId      = $driverId;
        $this->vehicleId     = $vehicleId;
    }
}
