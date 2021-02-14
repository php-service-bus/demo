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
     * @psalm-readonly
     *
     * @var string
     */
    public $correlationId;

    /**
     * Stored vehicle id
     *
     * @psalm-readonly
     *
     * @var VehicleId
     */
    public $id;

    /**
     * Vehicle brand
     *
     * @psalm-readonly
     *
     * @var VehicleBrand
     */
    public $brand;

    /**
     * Vehicle model name
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $model;

    /**
     * State registration number
     *
     * @psalm-readonly
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
        $this->id                 = clone $id;
        $this->brand              = clone $brand;
        $this->model              = $model;
        $this->registrationNumber = $registrationNumber;
    }
}
