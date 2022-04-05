<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add\Contract;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Add vehicle to driver profile
 *
 * @api
 * @see VehicleAddedToDriver
 * @see AddDriverVehicleValidationFailed
 * @see AddDriverVehicleFailed
 *
 * @psalm-immutable
 */
final class AddDriverVehicle
{
    /**
     * Request operation id
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $processId;

    /**
     * Driver identifier
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Driver id must be specified")
     *
     * @var string
     */
    public $driverId;

    /**
     * Vehicle brand
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Vehicle brand must be specified")
     *
     * @var string
     */
    public $vehicleBrand;

    /**
     * Vehicle model name
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Vehicle model must be specified")
     *
     * @var string
     */
    public $vehicleModel;

    /**
     * Year of release
     *
     * @psalm-readonly
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
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="State registration number must be specified")
     *
     * @var string
     */
    public $vehicleRegistrationNumber;

    /**
     * Vehicle color
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Car color must be specified")
     *
     * @var string
     */
    public $vehicleColor;

    public function __construct(
        string $processId,
        string $driverId,
        string $vehicleBrand,
        string $vehicleModel,
        int    $vehicleYear,
        string $vehicleRegistrationNumber,
        string $vehicleColor
    )
    {
        $this->processId                 = $processId;
        $this->driverId                  = $driverId;
        $this->vehicleBrand              = $vehicleBrand;
        $this->vehicleModel              = $vehicleModel;
        $this->vehicleYear               = $vehicleYear;
        $this->vehicleRegistrationNumber = $vehicleRegistrationNumber;
        $this->vehicleColor              = $vehicleColor;
    }
}
