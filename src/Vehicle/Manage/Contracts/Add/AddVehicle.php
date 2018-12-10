<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Contracts\Add;

use Desperado\ServiceBus\Common\Contract\Messages\Command;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Store new vehicle
 *
 * @api
 * @see VehicleAdded
 * @see AddVehicleValidationFailed
 * @see AddVehicleFailed
 */
final class AddVehicle implements Command
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

    /**
     * @param string $brand
     * @param string $model
     * @param int    $year
     * @param string $registrationNumber
     * @param string $color
     *
     * @return self
     */
    public static function create(string $brand, string $model, int $year, string $registrationNumber, string $color): self
    {
        $self = new self();

        $self->brand              = $brand;
        $self->model              = $model;
        $self->year               = $year;
        $self->registrationNumber = $registrationNumber;
        $self->color              = $color;

        return $self;
    }

    private function __construct()
    {

    }
}
