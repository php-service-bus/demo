<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
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
use ServiceBus\Sagas\Saga;
use ServiceBus\Sagas\Configuration\Annotations\SagaHeader;
use ServiceBus\Sagas\Configuration\Annotations\SagaEventListener;

/**
 * The saga of adding a car to the driver's profile
 *
 * @SagaHeader(
 *    idClass="App\DriverVehicle\AddDriverVehicleSagaId",
 *    containingIdProperty="correlationId",
 *    expireDateModifier="+5 minutes"
 * )
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
    public function start(object $command): void
    {
        /**
         * @noinspection PhpUnhandledExceptionInspection
         * @var \App\DriverVehicle\Contract\Manage\AddDriverVehicle $command
         */

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
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @return void
     */
    private function onStarted(): void
    {
        /**
         * @noinspection PhpUnhandledExceptionInspection
         * Try to add new vehicle
         */
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
     *
     * @throws \Throwable
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
     *
     * @throws \Throwable
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
     *
     * @throws \Throwable
     */
    private function doAddToDriver(): void
    {
        $this->storeRetryCount++;

        /** @var VehicleId $vehicleId */
        $vehicleId = $this->vehicleId;

        $this->fire(
            AddVehicleToDriverProfile::create($this->driverId, $vehicleId)
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
     *
     * @throws \Throwable
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
     *
     * @throws \Throwable
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
     *
     * @throws \Throwable
     */
    private function onAddDriverVehicleValidationFailed(AddDriverVehicleValidationFailed $event): void
    {
        $this->makeFailed(
            \sprintf('Validation failed (%s)', \http_build_query($event->violations))
        );
    }
}
