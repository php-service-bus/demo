<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Events;

use App\Vehicle\Brand\VehicleBrand;
use App\Vehicle\VehicleId;
use App\Vehicle\VehicleStatus;
use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 *
 */
final class VehicleAggregateCreated implements Event
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

    /**
     * @param VehicleId     $id
     * @param VehicleBrand  $brand
     * @param string        $model
     * @param int           $year
     * @param string        $registrationNumber
     * @param string        $color
     * @param VehicleStatus $status
     *
     * @return self
     */
    public static function create(
        VehicleId $id,
        VehicleBrand $brand,
        string $model,
        int $year,
        string $registrationNumber,
        string $color,
        VehicleStatus $status
    ): self
    {
        $self = new self();

        $self->id                 = $id;
        $self->brand              = $brand;
        $self->model              = $model;
        $self->year               = $year;
        $self->registrationNumber = $registrationNumber;
        $self->color              = $color;
        $self->status             = $status;

        return $self;
    }
}
