<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage;

use Amp\Promise;
use App\Vehicle\Brand\VehicleBrandFinder;
use App\Vehicle\Events\VehicleAggregateCreated;
use App\Vehicle\Manage\Contracts\Add\AddVehicle;
use App\Vehicle\Manage\Contracts\Add\AddVehicleValidationFailed;
use App\Vehicle\Manage\Contracts\Add\VehicleAdded;
use App\Vehicle\Vehicle;
use Desperado\ServiceBus\Application\KernelContext;
use Desperado\ServiceBus\EventSourcingProvider;
use Desperado\ServiceBus\Index\IndexKey;
use Desperado\ServiceBus\Index\IndexValue;
use Desperado\ServiceBus\IndexProvider;
use Desperado\ServiceBus\Services\Annotations\CommandHandler;
use Desperado\ServiceBus\Services\Annotations\EventListener;

/**
 *
 */
final class ManageVehicleService
{
    /**
     * Add new vehicle
     *
     * @CommandHandler(
     *     validate=true,
     *     defaultValidationFailedEvent="App\Vehicle\Manage\Contracts\Add\AddVehicleValidationFailed",
     *     defaultThrowableEvent="App\Vehicle\Manage\Contracts\Add\AddVehicleFailed"
     * )
     *
     * @param AddVehicle            $command
     * @param KernelContext         $context
     * @param VehicleBrandFinder    $vehicleBrandFinder
     * @param IndexProvider         $indexProvider
     * @param EventSourcingProvider $eventSourcingProvider
     *
     * @return \Generator
     */
    public function store(
        AddVehicle $command,
        KernelContext $context,
        VehicleBrandFinder $vehicleBrandFinder,
        IndexProvider $indexProvider,
        EventSourcingProvider $eventSourcingProvider
    ): \Generator
    {
        /** @var \App\Vehicle\Brand\VehicleBrand|null $brand */
        $brand = yield $vehicleBrandFinder->findOneByTitle($command->brand);

        if(null === $brand)
        {
            return $context->delivery(AddVehicleValidationFailed::invalidBrand($context->traceId()));
        }

        $vehicle = Vehicle::create($brand, $command->model, $command->year, $command->registrationNumber, $command->color);

        /** @var bool $canBeStored */
        $canBeStored = yield $indexProvider->add(
            IndexKey::create('vehicle', $command->registrationNumber),
            IndexValue::create((string) $vehicle->id())
        );

        /** Check the uniqueness of the state registration number */
        if(true === $canBeStored)
        {
            return yield $eventSourcingProvider->save($vehicle, $context);
        }

        return yield $context->delivery(AddVehicleValidationFailed::duplicateStateRegistrationNumber($context->traceId()));
    }

    /**
     * @EventListener()
     *
     * @param VehicleAggregateCreated $event
     * @param KernelContext           $context
     *
     * @return Promise
     */
    public function whenVehicleAggregateCreated(VehicleAggregateCreated $event, KernelContext $context): Promise
    {
        return $context->delivery(
            VehicleAdded::create(
                (string) $event->id, $event->brand->title(), $event->model, $event->registrationNumber, $context->traceId()
            )
        );
    }
}
