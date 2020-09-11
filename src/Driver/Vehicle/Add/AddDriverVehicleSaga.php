<?php /** @noinspection PhpUnusedPrivateMethodInspection */

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add;

use App\Driver\Commands\AddVehicleToDriver;
use App\Driver\DriverId;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicleFailed;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed;
use App\Driver\Vehicle\Add\Contract\VehicleAddedToDriver;
use App\Vehicle\Manage\Add\Contract\AddVehicle;
use App\Vehicle\Manage\Add\Contract\AddVehicleValidationFailed;
use App\Vehicle\Manage\Add\Contract\VehicleStored;
use App\Vehicle\VehicleId;
use ServiceBus\Sagas\Configuration\Annotations\SagaEventListener;
use ServiceBus\Sagas\Configuration\Annotations\SagaHeader;
use ServiceBus\Sagas\Saga;

/**
 * The saga of adding a car to the driver's profile
 *
 * @SagaHeader(
 *    idClass="App\Driver\Vehicle\Add\AddDriverVehicleSagaId",
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

    /**
     * Vehicle successfully stored
     *
     * @SagaEventListener()
     */
    private function onVehicleStored(VehicleStored $event): void
    {
        $this->vehicleId = $event->id;

        $this->doAddToDriver();
    }

    /**
     * Vehicle successfully added
     *
     * @SagaEventListener()
     */
    private function onVehicleAddedToDriver(VehicleAddedToDriver $event): void
    {
        $this->makeCompleted(\sprintf('Successful added (%s)', $event->correlationId));
    }

    /**
     * Parameter error when adding a vehicle
     *
     * @SagaEventListener()
     */
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

    /**
     * Add vehicle validation failed
     *
     * @SagaEventListener()
     */
    private function onAddDriverVehicleValidationFailed(AddDriverVehicleValidationFailed $event): void
    {
        $this->makeFailed(
            \sprintf('Validation failed (%s)', \http_build_query($event->violations))
        );
    }

    /**
     * An unknown error occurred while adding
     * We will try to repeat the operation
     *
     * @SagaEventListener()
     */
    private function onAddDriverVehicleFailed(AddDriverVehicleFailed $event): void
    {
        if ($this->storeRetryCount >= self::MAX_STORE_ATTEMPTS)
        {
            $this->doAddToDriver();

            return;
        }

        $this->makeFailed(
            \sprintf('Error adding vehicle to driver (%s)', $event->reason)
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
