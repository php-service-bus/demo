<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\CustomerRegistration;

use App\Customer\Customer;
use App\Customer\Events\CustomerCreated;
use App\CustomerRegistration\Contracts\CustomerRegistered;
use App\CustomerRegistration\Contracts\CustomerRegistrationFailed;
use App\CustomerRegistration\Contracts\RegisterCustomer;
use App\CustomerRegistration\Contracts\RegisterCustomerValidationFailed;
use ServiceBus\Context\KernelContext;
use ServiceBus\EventSourcing\Indexes\IndexKey;
use ServiceBus\EventSourcing\Indexes\IndexValue;
use ServiceBus\EventSourcingModule\EventSourcingProvider;
use ServiceBus\EventSourcingModule\IndexProvider;
use ServiceBus\Services\Annotations\CommandHandler;
use ServiceBus\Services\Annotations\EventListener;

/**
 *
 */
final class CustomerRegistrationService
{
    /**
     * Execute registration
     *
     * @CommandHandler(
     *     validate=true,
     *     defaultThrowableEvent="App\CustomerRegistration\Contracts\CustomerRegistrationFailed",
     *     defaultValidationFailedEvent="App\CustomerRegistration\Contracts\RegisterCustomerValidationFailed"
     * )
     *
     * @param RegisterCustomer      $command
     * @param KernelContext         $context
     * @param IndexProvider         $indexProvider
     * @param EventSourcingProvider $eventSourcingProvider
     *
     * @return \Generator
     *
     * @throws \Throwable
     */
    public function handle(
        RegisterCustomer $command,
        KernelContext $context,
        IndexProvider $indexProvider,
        EventSourcingProvider $eventSourcingProvider
    ): \Generator
    {
        $customer = Customer::register($command->phone, $command->email, $command->firstName, $command->lastName);

        /** @var bool $canBeRegistered */
        $canBeRegistered = yield $indexProvider->add(
            IndexKey::create('customer', $command->phone),
            IndexValue::create((string) $customer->id())
        );

        /** Check the uniqueness of the phone number */
        if(true === $canBeRegistered)
        {
            return yield $eventSourcingProvider->save($customer, $context);
        }

        return yield $context->delivery(
            RegisterCustomerValidationFailed::duplicatePhoneNumber($context->traceId())
        );
    }

    /**
     * @EventListener()
     *
     * @param CustomerCreated $event
     * @param KernelContext   $context
     *
     * @return \Generator
     */
    public function whenCustomerAggregateCreated(CustomerCreated $event, KernelContext $context): \Generator
    {
        yield $context->delivery(
            CustomerRegistered::create($event->id, $context->traceId())
        );

        $context->logContextMessage(
            'Customer with id "{customerId}" successfully added',
            ['customerId' => $event->id]
        );
    }

    /**
     * @EventListener()
     *
     * @param RegisterCustomerValidationFailed $event
     * @param KernelContext                    $context
     *
     * @return void
     */
    public function whenRegisterCustomerValidationFailed(
        RegisterCustomerValidationFailed $event,
        KernelContext $context
    ): void
    {
        $context->logContextMessage('Incorrect data to create a client', ['violations' => $event->violations]);
    }

    /**
     * @EventListener()
     *
     * @param CustomerRegistrationFailed $event
     * @param KernelContext              $context
     *
     * @return void
     */
    public function whenCustomerRegistrationFailed(CustomerRegistrationFailed $event, KernelContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the client registration process: %s', $event->reason)
            )
        );
    }
}
