<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Contracts\Add;

use ServiceBus\Common\Messages\Event;

/**
 * vehicle successful stored
 *
 * @api
 * @see AddVehicle
 *
 * @property-read string $correlationId
 * @property-read string $id
 * @property-read string $brand
 * @property-read string $model
 * @property-read string $registrationNumber
 */
final class VehicleAdded implements Event
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
     * @var string
     */
    public $id;

    /**
     * Vehicle brand
     *
     * @var string
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

    /**
     * @param string $id
     * @param string $brand
     * @param string $model
     * @param string $registrationNumber
     * @param string $correlationId
     *
     * @return self
     */
    public static function create(string $id, string $brand, string $model, string $registrationNumber, string $correlationId): self
    {
        return new self($id, $brand, $model, $registrationNumber, $correlationId);
    }

    /**
     * @param string $id
     * @param string $brand
     * @param string $model
     * @param string $registrationNumber
     * @param string $correlationId
     */
    private function __construct(string $id, string $brand, string $model, string $registrationNumber, string $correlationId)
    {
        $this->id                 = $id;
        $this->brand              = $brand;
        $this->model              = $model;
        $this->registrationNumber = $registrationNumber;
        $this->correlationId      = $correlationId;
    }
}
