<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Add\Contract;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Store new vehicle
 *
 * @api
 * @see VehicleStored
 * @see AddVehicleValidationFailed
 * @see AddVehicleFailed
 *
 * @psalm-immutable
 */
final class AddVehicle
{
    /**
     * Vehicle brand
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Vehicle brand must be specified")
     *
     * @var string
     */
    public $brand;

    /**
     * Vehicle model name
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Vehicle model must be specified")
     *
     * @var string
     */
    public $model;

    /**
     * Year of release
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Year of release must be specified")
     *
     * @var int
     */
    public $year;

    /**
     * State registration number
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="State registration number must be specified")
     *
     * @var string
     */
    public $registrationNumber;

    /**
     * Vehicle color
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Car color must be specified")
     *
     * @var string
     */
    public $color;

    public function __construct(string $brand, string $model, int $year, string $registrationNumber, string $color)
    {
        $this->brand              = $brand;
        $this->model              = $model;
        $this->year               = $year;
        $this->registrationNumber = $registrationNumber;
        $this->color              = $color;
    }
}
