<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
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
     * @Assert\NotBlank(message="Vehicle brand must be specified")
     *
     * @var string
     */
    public $brand;

    /**
     * Vehicle model name
     *
     * @Assert\NotBlank(message="Vehicle model must be specified")
     *
     * @var string
     */
    public $model;

    /**
     * Year of release
     *
     * @Assert\NotBlank(message="Year of release must be specified")
     * @Assert\Type("integer", message="Wrong year")
     *
     * @var int
     */
    public $year;

    /**
     * State registration number
     *
     * @Assert\NotBlank(message="State registration number must be specified")
     *
     * @var string
     */
    public $registrationNumber;

    /**
     * Vehicle color
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
