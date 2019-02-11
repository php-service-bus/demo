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

use ServiceBus\Common\Messages\Command;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Add vehicle to driver profile
 *
 * @api
 * @see VehicleAddedToDriver
 * @see AddDriverVehicleValidationFailed
 * @see AddDriverVehicleFailed
 *
 * @property-read string $driverId
 * @property-read string $vehicleBrand
 * @property-read string $vehicleModel
 * @property-read int    $vehicleYear
 * @property-read string $vehicleRegistrationNumber
 * @property-read string $vehicleColor
 */
final class AddDriverVehicle implements Command
{
    /**
     * Driver identifier
     *
     * @Assert\NotBlank(message="Driver id must be specified")
     *
     * @var string
     */
    public $driverId;

    /**
     * Vehicle brand
     *
     * @Assert\NotBlank(message="Vehicle brand must be specified")
     *
     * @var string
     */
    public $vehicleBrand;

    /**
     * Vehicle model name
     *
     * @Assert\NotBlank(message="Vehicle model must be specified")
     *
     * @var string
     */
    public $vehicleModel;

    /**
     * Year of release
     *
     * @Assert\NotBlank(message="Year of release must be specified")
     * @Assert\Type("integer", message="Wrong year")
     *
     * @var int
     */
    public $vehicleYear;

    /**
     * State registration number
     *
     * @Assert\NotBlank(message="State registration number must be specified")
     *
     * @var string
     */
    public $vehicleRegistrationNumber;

    /**
     * Vehicle color
     *
     * @Assert\NotBlank(message="Car color must be specified")
     *
     * @var string
     */
    public $vehicleColor;

    /**
     * @param string $driverId
     * @param string $vehicleBrand
     * @param string $vehicleModel
     * @param int    $vehicleYear
     * @param string $vehicleRegistrationNumber
     * @param string $vehicleColor
     *
     * @return self
     */
    public static function create(
        string $driverId,
        string $vehicleBrand,
        string $vehicleModel,
        int $vehicleYear,
        string $vehicleRegistrationNumber,
        string $vehicleColor
    ): self
    {
        return new self($driverId, $vehicleBrand, $vehicleModel, $vehicleYear, $vehicleRegistrationNumber, $vehicleColor);
    }

    /**
     * @param string $driverId
     * @param string $vehicleBrand
     * @param string $vehicleModel
     * @param int    $vehicleYear
     * @param string $vehicleRegistrationNumber
     * @param string $vehicleColor
     */
    private function __construct(
        string $driverId,
        string $vehicleBrand,
        string $vehicleModel,
        int $vehicleYear,
        string $vehicleRegistrationNumber,
        string $vehicleColor
    )
    {
        $this->driverId                  = $driverId;
        $this->vehicleBrand              = $vehicleBrand;
        $this->vehicleModel              = $vehicleModel;
        $this->vehicleYear               = $vehicleYear;
        $this->vehicleRegistrationNumber = $vehicleRegistrationNumber;
        $this->vehicleColor              = $vehicleColor;
    }
}
