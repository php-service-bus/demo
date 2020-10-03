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

use App\Vehicle\Brand\VehicleBrand;
use App\Vehicle\VehicleId;

/**
 * Vehicle successful stored
 *
 * @api
 * @see AddVehicle
 *
 * @psalm-immutable
 */
final class VehicleStored
{
    /**
     * Request operation id
     *
     * @var string
     */
    public $correlationId;

    /**
     * Stored vehicle id
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
     * State registration number
     *
     * @var string
     */
    public $registrationNumber;

    public function __construct(
        string $correlationId,
        VehicleId $id,
        VehicleBrand $brand,
        string $model,
        string $registrationNumber
    ) {
        $this->correlationId      = $correlationId;
        $this->id                 = $id;
        $this->brand              = clone $brand;
        $this->model              = $model;
        $this->registrationNumber = $registrationNumber;
    }
}
