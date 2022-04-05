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
use App\Driver\Driver;
use App\Driver\ManageDocument\Add\Contract\AddDriverDocumentValidationFailed;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\EventSourcing\EventSourcingProvider;
use ServiceBus\EventSourcing\Exceptions\AggregateNotFound;
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
        AddVehicleToDriver    $command,
        ServiceBusContext     $context,
        EventSourcingProvider $eventSourcingProvider
    ): Promise
    {
        return call(
            static function() use ($command, $context, $eventSourcingProvider): \Generator
            {
                try
                {
                    yield $eventSourcingProvider->load(
                        $command->driverId,
                        $context,
                        static function(Driver $driver) use ($command): void
                        {
                            $driver->addVehicle($command->vehicleId);
                        }
                    );

                    yield $context->delivery(
                        AddDriverVehicleValidationFailed::driverNotFound($command->driverId)
                    );
                }
                catch(AggregateNotFound)
                {
                    yield $context->delivery(
                        AddDriverDocumentValidationFailed::driverNotFound($command->driverId)
                    );
                }
            }
        );
    }
}
