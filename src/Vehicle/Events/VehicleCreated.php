<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Events;

use App\Vehicle\Brand\VehicleBrand;
use App\Vehicle\VehicleId;
use App\Vehicle\VehicleStatus;

/**
 * Vehicle aggregate created
 *
 * @internal
 *
 * @@psalm-immutable
 */
final class VehicleCreated
{
    /**
     * Aggregate identifier
     *
     * @var VehicleId
     */
    public $id;

    /**
     * Vehicle brand
     *
     * @var VehicleBrand
     */
    public $brand;

    /**
     * Vehicle model name
     *
     * @var string
     */
    public $model;

    /**
     * Year of release
     *
     * @var int
     */
    public $year;

    /**
     * State registration number
     *
     * @var string
     */
    public $registrationNumber;

    /**
     * Car color
     *
     * @var string
     */
    public $color;

    /**
     * Car status
     *
     * @var VehicleStatus
     */
    public $status;

    public function __construct(
        VehicleId $id,
        VehicleBrand $brand,
        string $model,
        int $year,
        string $registrationNumber,
        string $color,
        VehicleStatus $status
    ) {
        $this->id                 = $id;
        $this->brand              = $brand;
        $this->model              = $model;
        $this->year               = $year;
        $this->registrationNumber = $registrationNumber;
        $this->color              = $color;
        $this->status             = $status;
    }
}
