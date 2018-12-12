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

use Amp\Promise;
use App\Driver\Events\VehicleAddedToAggregate;
use App\DriverVehicle\Commands\AddVehicleToDriverProfile;
use App\DriverVehicle\Contract\Manage\AddDriverVehicle;
use App\DriverVehicle\Contract\Manage\AddDriverVehicleFailed;
use App\DriverVehicle\Contract\Manage\AddDriverVehicleValidationFailed;
use App\DriverVehicle\Contract\Manage\VehicleAddedToDriver;
use Desperado\ServiceBus\Application\KernelContext;
use Desperado\ServiceBus\EventSourcingProvider;
use Desperado\ServiceBus\SagaProvider;
use Desperado\ServiceBus\Services\Annotations\CommandHandler;
use Desperado\ServiceBus\Services\Annotations\EventListener;

/**
 *
 */
final class DriverVehicleManageService
{
    /**
     * Add new vehicle to driver profile
     *
     * @CommandHandler(
     *     validate=true,
     *     defaultValidationFailedEvent="App\DriverVehicle\Contract\Manage\AddDriverVehicleValidationFailed",
     *     defaultThrowableEvent="App\DriverVehicle\Contract\Manage\AddDriverVehicleFailed"
     * )
     *
     * @param AddDriverVehicle $command
     * @param KernelContext    $context
     * @param SagaProvider     $sagaProvider
     *
     * @return \Generator
     */
    public function add(AddDriverVehicle $command, KernelContext $context, SagaProvider $sagaProvider): \Generator
    {
        yield $sagaProvider->start(
            new AddDriverVehicleSagaId($context->traceId(), AddDriverVehicleSaga::class),
            $command,
            $context
        );
    }

    /**
     * Add vehicle to aggregate (internal usage)
     *
     * @CommandHandler()
     *
     * @param AddVehicleToDriverProfile $command
     * @param KernelContext             $context
     * @param EventSourcingProvider     $eventSourcingProvider
     *
     * @return \Generator
     */
    public function processAdd(
        AddVehicleToDriverProfile $command,
        KernelContext $context,
        EventSourcingProvider $eventSourcingProvider
    ): \Generator
    {
        /** @var \App\Driver\Driver|null $driver */
        $driver = yield $eventSourcingProvider->load($command->driverId);

        if(null !== $driver)
        {
            $driver->addVehicle($command->vehicleId);

            return yield $eventSourcingProvider->save($driver, $context);
        }

        return yield $context->delivery(
            AddDriverVehicleValidationFailed::driverNotFound($context->traceId())
        );
    }

    /**
     * @EventListener()
     *
     * @param VehicleAddedToAggregate $event
     * @param KernelContext           $context
     *
     * @return Promise
     */
    public function whenVehicleAddedToAggregate(VehicleAddedToAggregate $event, KernelContext $context): Promise
    {
        return $context->delivery(
            VehicleAddedToDriver::create($context->traceId(), (string) $event->driverId, (string) $event->vehicleId)
        );
    }

    /**
     * @EventListener()
     *
     * @param VehicleAddedToDriver $event
     * @param KernelContext        $context
     *
     * @return void
     */
    public function whenVehicleAddedToDriver(VehicleAddedToDriver $event, KernelContext $context): void
    {
        $context->logContextMessage('Vehicle successfully added to driver profile', [
            'driverId'  => $event->driverId,
            'vehicleId' => $event->vehicleId
        ]);
    }

    /**
     * @EventListener()
     *
     * @param AddDriverVehicleValidationFailed $event
     * @param KernelContext                    $context
     *
     * @return void
     */
    public function whenAddDriverVehicleValidationFailed(
        AddDriverVehicleValidationFailed $event,
        KernelContext $context
    ): void
    {
        $context->logContextMessage(
            'Validation error in the process of saving the vehicle for the driver', [
                'violations' => $event->violations
            ]
        );
    }

    /**
     * @EventListener()
     *
     * @param AddDriverVehicleFailed $event
     * @param KernelContext          $context
     *
     * @return void
     */
    public function whenAddDriverVehicleFailed(AddDriverVehicleFailed $event, KernelContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the process of saving the vehicle for the driver: %s', $event->reason)
            )
        );
    }
}
