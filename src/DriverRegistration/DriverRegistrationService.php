<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverRegistration;

use App\Driver\Driver;
use App\Driver\Events\DriverAggregateCreated;
use App\DriverRegistration\Contracts\DriverRegistered;
use App\DriverRegistration\Contracts\DriverRegistrationFailed;
use App\DriverRegistration\Contracts\RegisterDriver;
use App\DriverRegistration\Contracts\RegisterDriverValidationFailed;
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
final class DriverRegistrationService
{
    /**
     * Register new driver
     *
     * @CommandHandler(
     *     validate=true,
     *     defaultValidationFailedEvent="App\DriverRegistration\Contracts\RegisterDriverValidationFailed",
     *     defaultThrowableEvent="App\DriverRegistration\Contracts\DriverRegistrationFailed"
     * )
     *
     * @param RegisterDriver        $command
     * @param KernelContext         $context
     * @param IndexProvider         $indexProvider
     * @param EventSourcingProvider $eventSourcingProvider
     *
     * @return \Generator
     */
    public function handle(
        RegisterDriver $command,
        KernelContext $context,
        IndexProvider $indexProvider,
        EventSourcingProvider $eventSourcingProvider
    ): \Generator
    {
        $driver = Driver::register(
            $command->phone, $command->email, $command->firstName, $command->lastName, $command->patronymic
        );

        /** @var bool $canBeRegistered */
        $canBeRegistered = yield $indexProvider->add(
            IndexKey::create('driver', $command->phone),
            IndexValue::create((string) $driver->id())
        );

        /** Check the uniqueness of the phone number */
        if(true === $canBeRegistered)
        {
            return yield $eventSourcingProvider->save($driver, $context);
        }

        return yield $context->delivery(
            RegisterDriverValidationFailed::duplicatePhoneNumber($context->traceId())
        );
    }

    /**
     * @EventListener()
     *
     * @param DriverAggregateCreated $event
     * @param KernelContext          $context
     *
     * @return \Generator
     */
    public function whenDriverAggregateCreated(DriverAggregateCreated $event, KernelContext $context): \Generator
    {
        yield $context->delivery(
            DriverRegistered::create($event->id, $context->traceId())
        );

        $context->logContextMessage(
            'Driver with id "{driverId}" successfully added',
            ['driverId' => $event->id]
        );
    }

    /**
     * @EventListener()
     *
     * @param RegisterDriverValidationFailed $event
     * @param KernelContext                  $context
     *
     * @return void
     */
    public function whenRegisterDriverValidationFailed(
        RegisterDriverValidationFailed $event,
        KernelContext $context
    ): void
    {
        $context->logContextMessage('Incorrect data to create a driver', ['violations' => $event->violations]);
    }

    /**
     * @EventListener()
     *
     * @param DriverRegistrationFailed $event
     * @param KernelContext            $context
     *
     * @return void
     */
    public function whenDriverRegistrationFailed(DriverRegistrationFailed $event, KernelContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the driver registration process: %s', $event->reason)
            )
        );
    }
}
