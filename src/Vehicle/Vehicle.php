<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle;

use App\Vehicle\Brand\VehicleBrand;
use App\Vehicle\Events\VehicleAggregateCreated;
use ServiceBus\EventSourcing\Aggregate;

/**
 * Vehicle aggregate
 */
final class Vehicle extends Aggregate
{
    /**
     * Vehicle brand
     *
     * @var VehicleBrand
     */
    private $brand;

    /**
     * Vehicle model name
     *
     * @var string
     */
    private $model;

    /**
     * Year of release
     *
     * @var int
     */
    private $year;

    /**
     * State registration number
     *
     * @var string
     */
    private $registrationNumber;

    /**
     * Car color
     *
     * @var string
     */
    private $color;

    /**
     * Car status
     *
     * @var VehicleStatus
     */
    private $status;

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param VehicleBrand $brand
     * @param string       $model
     * @param int          $year
     * @param string       $registrationNumber
     * @param string       $color
     *
     * @return self
     */
    public static function create(
        VehicleBrand $brand,
        string $model,
        int $year,
        string $registrationNumber,
        string $color
    ): self
    {
        $id   = VehicleId::new();
        $self = new self($id);

        /** @noinspection PhpUnhandledExceptionInspection */
        $self->raise(
            VehicleAggregateCreated::create(
                $id, $brand, $model, $year, $registrationNumber, $color, VehicleStatus::moderation()
            )
        );

        return $self;
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param VehicleAggregateCreated $event
     *
     * @return void
     */
    private function onVehicleAggregateCreated(VehicleAggregateCreated $event): void
    {
        $this->brand              = $event->brand;
        $this->model              = $event->model;
        $this->year               = $event->year;
        $this->registrationNumber = $event->registrationNumber;
        $this->color              = $event->color;
        $this->status             = $event->status;
    }
}
