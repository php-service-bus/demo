<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Add;

use Amp\Promise;
use App\Vehicle\Brand\VehicleBrandFinder;
use App\Vehicle\Manage\Add\Contract\AddVehicle;
use App\Vehicle\Manage\Add\Contract\AddVehicleValidationFailed;
use App\Vehicle\Vehicle;
use App\Vehicle\VehicleId;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\EventSourcing\EventSourcingProvider;
use ServiceBus\EventSourcing\Indexes\IndexKey;
use ServiceBus\EventSourcing\Indexes\IndexValue;
use ServiceBus\EventSourcing\IndexProvider;
use ServiceBus\Services\Attributes\CommandHandler;
use function Amp\call;

/**
 * Add new vehicle
 */
final class HandleAddVehicle
{
    #[CommandHandler(
        description: 'Add new vehicle',
        validationEnabled: true
    )]
    public function handle(
        AddVehicle $command,
        ServiceBusContext $context,
        VehicleBrandFinder $vehicleBrandFinder,
        IndexProvider $indexProvider,
        EventSourcingProvider $eventSourcingProvider
    ): Promise
    {
        return call(
            static function() use ($command, $context, $vehicleBrandFinder, $indexProvider, $eventSourcingProvider): \Generator
            {
                $violations = $context->violations();

                if($violations !== null)
                {
                    return yield $context->delivery(
                        new AddVehicleValidationFailed($command->registrationNumber, $violations)
                    );
                }

                /** @var \App\Vehicle\Brand\VehicleBrand|null $brand */
                $brand = yield $vehicleBrandFinder->findOneByTitle($command->brand);

                if($brand === null)
                {
                    return $context->delivery(AddVehicleValidationFailed::invalidBrand($command->registrationNumber));
                }

                $vehicle = Vehicle::create(
                    $brand,
                    $command->model,
                    $command->year,
                    $command->registrationNumber,
                    $command->color
                );

                $indexKey = new IndexKey('vehicle', $command->registrationNumber);

                /** @var IndexValue|null $storedValue */
                $storedValue = yield $indexProvider->get($indexKey);

                /** Vehicle doesn`t exist  */
                if($storedValue === null)
                {
                    yield $indexProvider->add($indexKey, new IndexValue($vehicle->id()->toString()));

                    return yield $eventSourcingProvider->store($vehicle, $context);
                }

                return yield $context->delivery(
                    AddVehicleValidationFailed::duplicateStateRegistrationNumber(
                        $command->registrationNumber,
                        new VehicleId((string) $storedValue->value)
                    )
                );
            }
        );
    }
}
