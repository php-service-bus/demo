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

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * vehicle successful stored
 *
 * @api
 * @see AddVehicle
 */
final class VehicleAdded implements Event
{
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
     * Request Id
     *
     * @var string
     */
    public $correlationId;

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
        $self = new self();

        $self->id                 = $id;
        $self->brand              = $brand;
        $self->model              = $model;
        $self->registrationNumber = $registrationNumber;
        $self->correlationId      = $correlationId;

        return $self;
    }

    private function __construct()
    {

    }
}
