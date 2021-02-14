<?php /** @noinspection PhpUnusedPrivateMethodInspection */

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle;

use App\Vehicle\Brand\VehicleBrand;
use App\Vehicle\Events\VehicleCreated;
use ServiceBus\EventSourcing\Aggregate;

/**
 * Vehicle aggregate
 *
 * @method VehicleId id()
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

    public static function create(
        VehicleBrand $brand,
        string $model,
        int $year,
        string $registrationNumber,
        string $color
    ): self {
        $self = new self(VehicleId::new());

        $self->raise(
            new VehicleCreated(
                $self->id(),
                $brand,
                $model,
                $year,
                $registrationNumber,
                $color,
                VehicleStatus::moderation()
            )
        );

        return $self;
    }

    private function onVehicleCreated(VehicleCreated $event): void
    {
        $this->brand              = $event->brand;
        $this->model              = $event->model;
        $this->year               = $event->year;
        $this->registrationNumber = $event->registrationNumber;
        $this->color              = $event->color;
        $this->status             = $event->status;
    }
}
