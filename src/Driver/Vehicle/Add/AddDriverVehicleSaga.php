<?php /** @noinspection PhpUnusedPrivateMethodInspection */

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add;

use App\Driver\Commands\AddVehicleToDriver;
use App\Driver\DriverId;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed;
use App\Driver\Vehicle\Add\Contract\VehicleAddedToDriver;
use App\Vehicle\Manage\Add\Contract\AddVehicle;
use App\Vehicle\Manage\Add\Contract\AddVehicleValidationFailed;
use App\Vehicle\Manage\Add\Contract\VehicleStored;
use App\Vehicle\VehicleId;
use ServiceBus\Sagas\Configuration\Attributes\SagaEventListener;
use ServiceBus\Sagas\Configuration\Attributes\SagaHeader;
use ServiceBus\Sagas\Saga;

/**
 * The saga of adding a car to the driver's profile
 */
#[SagaHeader(
    idClass: AddDriverVehicleSagaId::class,
    containingIdProperty: 'correlationId',
    expireDateModifier: '+5 minutes'
)]
final class AddDriverVehicleSaga extends Saga
{
    /**
     * Driver identifier
     *
     * @var DriverId
     */
    private $driverId;

    /**
     * Vehicle identifier
     *
     * @var VehicleId|null
     */
    private $vehicleId;

    /**
     * Vehicle brand
     *
     * @var string
     */
    private $vehicleBrand;

    /**
     * Vehicle model name
     *
     * @var string
     */
    private $vehicleModel;

    /**
     * Year of release
     *
     * @var int
     */
    private $vehicleYear;

    /**
     * State registration number
     *
     * @var string
     */
    private $vehicleRegistrationNumber;

    /**
     * Vehicle color
     *
     * @var string
     */
    private $vehicleColor;

    /**
     * The number of attempts to save the vehicle in the driver's profile
     *
     * @var int
     */
    private $storeRetryCount = 0;

    public function start(object $command): void
    {
        /** @var \App\Driver\Vehicle\Add\Contract\AddDriverVehicle $initCommand */

        $initCommand = $command;

        $this->driverId                  = new DriverId($initCommand->driverId);
        $this->vehicleBrand              = $initCommand->vehicleBrand;
        $this->vehicleModel              = $initCommand->vehicleModel;
        $this->vehicleYear               = $initCommand->vehicleYear;
        $this->vehicleRegistrationNumber = $initCommand->vehicleRegistrationNumber;
        $this->vehicleColor              = $initCommand->vehicleColor;

        $this->onStarted();
    }

    private function onStarted(): void
    {
        /** Try to add new vehicle */
        $this->fire(
            new AddVehicle(
                $this->vehicleBrand,
                $this->vehicleModel,
                $this->vehicleYear,
                $this->vehicleRegistrationNumber,
                $this->vehicleColor
            )
        );
    }

    #[SagaEventListener(
        description: 'Vehicle successfully stored'
    )]
    private function onVehicleStored(VehicleStored $event): void
    {
        $this->vehicleId = $event->id;

        $this->doAddToDriver();
    }

    #[SagaEventListener(
        description: 'Vehicle successfully added'
    )]
    private function onVehicleAddedToDriver(VehicleAddedToDriver $event): void
    {
        $this->makeCompleted(\sprintf('Successful added (%s)', $event->correlationId));
    }

    #[SagaEventListener(
        description: 'Parameter error when adding a vehicle'
    )]
    private function onAddVehicleValidationFailed(AddVehicleValidationFailed $event): void
    {
        /** Vehicle has been added previously */
        if ($event->vehicleId !== null)
        {
            $this->vehicleId = $event->vehicleId;

            $this->doAddToDriver();

            return;
        }

        /** Another validation errors */
        $this->makeFailed('Validation failed');
    }

    #[SagaEventListener(
        description: 'Add vehicle validation failed'
    )]
    private function onAddDriverVehicleValidationFailed(AddDriverVehicleValidationFailed $event): void
    {
        $this->makeFailed(
            \sprintf('Validation failed (%s)', \http_build_query($event->violations))
        );
    }

    /**
     * Add vehicle to driver profile
     */
    private function doAddToDriver(): void
    {
        $this->storeRetryCount++;

        /** @var VehicleId $vehicleId */
        $vehicleId = $this->vehicleId;

        $this->fire(
            new AddVehicleToDriver($this->driverId, $vehicleId)
        );
    }
}
