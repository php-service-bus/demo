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
use App\Driver\Vehicle\Add\Contract\AddDriverVehicle;
use App\Driver\Vehicle\Add\Contract\AddDriverVehicleValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Sagas\SagasProvider;
use ServiceBus\Services\Attributes\CommandHandler;

/**
 * Add new vehicle to driver profile
 */
final class HandleAddDriverVehicle
{
    #[CommandHandler(
        description: 'Add new vehicle to driver profile',
        validationEnabled: true
    )]
    public function handle(
        AddDriverVehicle $command,
        ServiceBusContext $context,
        SagasProvider $sagasProvider
    ): Promise
    {
        $violations = $context->violations();

        if($violations !== null)
        {
            return $context->delivery(
                new AddDriverVehicleValidationFailed($context->metadata()->traceId(), $violations)
            );
        }

        return $sagasProvider->start(
            new AddDriverVehicleSagaId($context->metadata()->traceId(), AddDriverVehicleSaga::class),
            $command,
            $context
        );
    }
}
