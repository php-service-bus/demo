<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add;

use Amp\Promise;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicle;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Sagas\SagasProvider;
use ServiceBus\Services\Annotations\CommandHandler;

/**
 * Add new vehicle to driver profile
 */
final class HandleAddDriverVehicle
{
    /**
     * @CommandHandler(
     *     description="Add new vehicle to driver profile",
     *     validate=true,
     *     defaultValidationFailedEvent="App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed",
     *     defaultThrowableEvent="App\Driver\Vehicle\Add\Contract\AddDriverVehicleFailed"
     * )
     */
    public function handle(
        AddDriverVehicle $command,
        ServiceBusContext $context,
        SagasProvider $sagasProvider
    ): Promise {
        return $sagasProvider->start(
            new AddDriverVehicleSagaId($context->traceId(), AddDriverVehicleSaga::class),
            $command,
            $context
        );
    }
}
