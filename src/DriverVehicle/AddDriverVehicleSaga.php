<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverVehicle;

use App\Driver\DriverId;
use App\DriverVehicle\Commands\AddVehicleToDriverProfile;
use App\DriverVehicle\Contract\Manage\AddDriverVehicleFailed;
use App\DriverVehicle\Contract\Manage\VehicleAddedToDriver;
use App\Vehicle\Manage\Contracts as VehicleManageContracts;
use App\DriverVehicle\Contract\Manage\AddDriverVehicleValidationFailed;
use App\Vehicle\VehicleId;
use Desperado\ServiceBus\Common\Contract\Messages\Command;
use Desperado\ServiceBus\Sagas\Annotations\SagaEventListener;
use Desperado\ServiceBus\Sagas\Annotations\SagaHeader;
use Desperado\ServiceBus\Sagas\Saga;

/**
 * The saga of adding a car to the driver's profile
 *
 * @SagaHeader(
 *    idClass="App\DriverVehicle\AddDriverVehicleSagaId",
 *    containingIdProperty="correlationId",
 *    expireDateModifier="+5 minutes"
 * )
 *
 * @method AddDriverVehicleSagaId id
 */
final class AddDriverVehicleSaga extends Saga
{
    /** Maximum number of attempts to save a vehicle in the driver's profile  */
    private const MAX_STORE_ATTEMPTS = 5;

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

    /**
     * @inheritDoc
     */
    public function start(Command $command): void
    {
        /** @var \App\DriverVehicle\Contract\Manage\AddDriverVehicle $command */

        $this->driverId                  = new DriverId($command->driverId);
        $this->vehicleBrand              = $command->vehicleBrand;
        $this->vehicleModel              = $command->vehicleModel;
        $this->vehicleYear               = $command->vehicleYear;
        $this->vehicleRegistrationNumber = $command->vehicleRegistrationNumber;
        $this->vehicleColor              = $command->vehicleColor;

        $this->onStarted();
    }

    /**
     * Saga started
     *
     * @return void
     */
    private function onStarted(): void
    {
        /** Try to add new vehicle */
        $this->fire(
            VehicleManageContracts\Add\AddVehicle::create(
                $this->vehicleBrand,
                $this->vehicleModel,
                $this->vehicleYear,
                $this->vehicleRegistrationNumber,
                $this->vehicleColor
            )
        );
    }

    /**
     * Parameter error when adding a vehicle
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @SagaEventListener()
     *
     * @param VehicleManageContracts\Add\AddVehicleValidationFailed $event
     *
     * @return void
     */
    private function onAddVehicleValidationFailed(VehicleManageContracts\Add\AddVehicleValidationFailed $event): void
    {
        /** Vehicle has been added previously */
        if(null !== $event->vehicleId)
        {
            $this->vehicleId = new VehicleId($event->vehicleId);

            $this->doAddToDriver();

            return;
        }

        /** Another validation errors */

        $this->raise(
            AddDriverVehicleValidationFailed::create($event->correlationId, $event->violations)
        );

        $this->makeFailed('Validation failed');
    }

    /**
     * Vehicle successful created
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @SagaEventListener()
     *
     * @param VehicleManageContracts\Add\VehicleAdded $event
     *
     * @return void
     */
    private function onVehicleAdded(VehicleManageContracts\Add\VehicleAdded $event): void
    {
        $this->vehicleId = new VehicleId($event->id);

        $this->doAddToDriver();
    }

    /**
     * Add vehicle to driver profile
     *
     * @return void
     */
    private function doAddToDriver(): void
    {
        $this->storeRetryCount++;

        $this->fire(
            AddVehicleToDriverProfile::create($this->driverId, $this->vehicleId)
        );
    }

    /**
     * Vehicle successfully added
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @SagaEventListener()
     *
     * @param VehicleAddedToDriver $event
     *
     * @return void
     */
    private function onVehicleAddedToDriver(VehicleAddedToDriver $event): void
    {
        $this->makeCompleted(\sprintf('Successful added (%s)', $event->correlationId));
    }

    /**
     * An unknown error occurred while adding
     * We will try to repeat the operation
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @SagaEventListener()
     *
     * @param AddDriverVehicleFailed $event
     *
     * @return void
     */
    private function onAddDriverVehicleFailed(AddDriverVehicleFailed $event): void
    {
        if(self::MAX_STORE_ATTEMPTS <= $this->storeRetryCount)
        {
            $this->doAddToDriver();

            return;
        }

        $this->makeFailed(
            \sprintf('Error adding vehicle to driver (%s)', $event->reason)
        );
    }

    /**
     * Add vehicle validation failed
     *
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @SagaEventListener()
     *
     * @param AddDriverVehicleValidationFailed $event
     *
     * @return void
     */
    private function onAddDriverVehicleValidationFailed(AddDriverVehicleValidationFailed $event): void
    {
        $this->makeFailed(
            \sprintf('Validation failed (%s)', \http_build_query($event->violations))
        );
    }
}
