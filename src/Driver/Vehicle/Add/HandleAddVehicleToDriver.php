<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add;

use Amp\Promise;
use App\Driver\Commands\AddVehicleToDriver;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\EventSourcing\EventSourcingProvider;
use ServiceBus\Services\Attributes\CommandHandler;
use function Amp\call;

/**
 *
 */
final class HandleAddVehicleToDriver
{
    #[CommandHandler(
        description: 'Add vehicle to driver aggregate'
    )]
    public function handle(
        AddVehicleToDriver $command,
        ServiceBusContext $context,
        EventSourcingProvider $eventSourcingProvider
    ): Promise {
        return call(
            static function () use ($command, $context, $eventSourcingProvider): \Generator
            {
                /** @var \App\Driver\Driver|null $driver */
                $driver = yield $eventSourcingProvider->load($command->driverId);

                if ($driver !== null)
                {
                    $driver->addVehicle($command->vehicleId);

                    return yield $eventSourcingProvider->save($driver, $context);
                }

                return yield $context->delivery(
                    AddDriverVehicleValidationFailed::driverNotFound($context->metadata()->traceId())
                );
            }
        );
    }
}
