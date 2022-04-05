<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration;

use Amp\Promise;
use App\Driver\Driver;
use App\Driver\DriverContacts;
use App\Driver\DriverFullName;
use App\Driver\Registration\Contract\RegisterDriver;
use App\Driver\Registration\Contract\RegisterDriverValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\EventSourcing\EventSourcingProvider;
use ServiceBus\EventSourcing\Indexes\IndexKey;
use ServiceBus\EventSourcing\Indexes\IndexValue;
use ServiceBus\EventSourcing\IndexProvider;
use ServiceBus\Services\Attributes\CommandHandler;
use function Amp\call;

/**
 * Execute driver registration
 */
final class HandleRegisterDriver
{
    #[CommandHandler(
        description: 'Execute driver registration',
        validationEnabled: true
    )]
    public function handle(
        RegisterDriver $command,
        ServiceBusContext $context,
        IndexProvider $indexProvider,
        EventSourcingProvider $eventSourcingProvider
    ): Promise
    {
        return call(
            static function() use ($command, $context, $indexProvider, $eventSourcingProvider): \Generator
            {
                $violations = $context->violations();

                if($violations !== null)
                {
                    return yield $context->delivery(
                        new RegisterDriverValidationFailed($context->metadata()->traceId(), $violations)
                    );
                }

                $driver = Driver::register(
                    new DriverFullName($command->firstName, $command->lastName, $command->patronymic),
                    new DriverContacts($command->phone, $command->email)
                );

                /** @var bool $canBeRegistered */
                $canBeRegistered = yield $indexProvider->add(
                    new IndexKey('driver', $command->phone),
                    new IndexValue($driver->id()->toString())
                );

                /** Check the uniqueness of the phone number */
                if($canBeRegistered)
                {
                    return yield $eventSourcingProvider->store($driver, $context);
                }

                return yield $context->delivery(
                    RegisterDriverValidationFailed::duplicatePhoneNumber($context->metadata()->traceId())
                );
            }
        );
    }
}
