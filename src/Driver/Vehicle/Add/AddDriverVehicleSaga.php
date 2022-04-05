<?php

/** @noinspection PhpUnusedPrivateMethodInspection */

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver\Vehicle\Add;

use App\Driver\Commands\AddVehicleToDriver;
use App\Driver\DriverId;
use App\Driver\Events\VehicleAdded;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicle;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed;
use App\Vehicle\Events\VehicleCreated;
use App\Vehicle\Manage\Add\Contract\AddVehicle;
use App\Vehicle\Manage\Add\Contract\AddVehicleValidationFailed;
use App\Vehicle\VehicleId;
use ServiceBus\Sagas\Configuration\Attributes\SagaEventListener;
use ServiceBus\Sagas\Configuration\Attributes\SagaHeader;
use ServiceBus\Sagas\Configuration\Attributes\SagaInitialHandler;
use ServiceBus\Sagas\Saga;

/**
 * The saga of adding a car to the driver's profile
 */
#[SagaHeader(
    idClass: AddDriverVehicleSagaId::class,
    containingIdProperty: 'processId',
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

    #[SagaInitialHandler]
    public function start(AddDriverVehicle $command): void
    {
        $this->driverId                  = $command->driverId;
        $this->vehicleBrand              = $command->vehicleBrand;
        $this->vehicleModel              = $command->vehicleModel;
        $this->vehicleYear               = $command->vehicleYear;
        $this->vehicleRegistrationNumber = $command->vehicleRegistrationNumber;
        $this->vehicleColor              = $command->vehicleColor;

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

        $this->associateWith('registrationNumber', $this->vehicleRegistrationNumber);
    }

    #[SagaEventListener(
        containingIdProperty: 'registrationNumber',
        description: 'Vehicle successfully stored',
    )]
    private function onVehicleCreated(VehicleCreated $event): void
    {
        $this->removeAssociation('registrationNumber');

        $this->vehicleId = $event->id;

        $this->doAddToDriver();
    }

    #[SagaEventListener(
        containingIdProperty: 'driverId',
        description: 'Vehicle successfully added'
    )]
    private function onVehicleAdded(VehicleAdded $event): void
    {
        $this->removeAssociation('driverId');
        $this->complete();
    }

    #[SagaEventListener(
        containingIdProperty: 'registrationNumber',
        description: 'Parameter error when adding a vehicle'
    )]
    private function onAddVehicleValidationFailed(AddVehicleValidationFailed $event): void
    {
        $this->removeAssociation('registrationNumber');

        /** Vehicle has been added previously */
        if ($event->vehicleId !== null)
        {
            $this->vehicleId = $event->vehicleId;

            $this->doAddToDriver();

            return;
        }

        /** Another validation errors */
        $this->complete('Validation failed');
    }

    #[SagaEventListener(
        containingIdProperty: 'driverId',
        description: 'Add vehicle validation failed'
    )]
    private function onAddDriverVehicleValidationFailed(AddDriverVehicleValidationFailed $event): void
    {
        $this->removeAssociation('driverId');
        $this->fail(
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

        $this->associateWith('driverId', $this->driverId->toString());
        $this->fire(
            new AddVehicleToDriver($this->driverId, $vehicleId)
        );
    }
}
