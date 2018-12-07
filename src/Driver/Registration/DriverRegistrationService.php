<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration;

use Amp\Promise;
use App\Driver\Driver;
use App\Driver\Events\DriverAggregateCreated;
use App\Driver\Registration\Contracts\DriverRegistered;
use App\Driver\Registration\Contracts\DriverRegistrationFailed;
use App\Driver\Registration\Contracts\RegisterDriver;
use App\Driver\Registration\Contracts\RegisterDriverValidationFailed;
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
     * @CommandHandler(validate=true)
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
        try
        {
            if(false === $context->isValid())
            {
                return yield $context->delivery(
                    RegisterDriverValidationFailed::create($context->traceId(), $context->violations())
                );
            }

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

            return yield $context->delivery(RegisterDriverValidationFailed::duplicatePhoneNumber($context->traceId()));
        }
        catch(\Throwable $throwable)
        {
            return yield $context->delivery(
                DriverRegistrationFailed::create($context->traceId(), $throwable->getMessage())
            );
        }
    }

    /**
     * @EventListener()
     *
     * @param DriverAggregateCreated $event
     * @param KernelContext          $context
     *
     * @return Promise
     */
    public function whenDriverAggregateCreated(DriverAggregateCreated $event, KernelContext $context): Promise
    {
        return $context->delivery(DriverRegistered::create($event->id, $context->traceId()));
    }
}
