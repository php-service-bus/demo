<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Register;

use Amp\Promise;
use App\Customer\Customer;
use App\Customer\Events\CustomerAggregateCreated;
use App\Customer\Register\Contracts\CustomerRegistered;
use App\Customer\Register\Contracts\RegisterCustomer;
use App\Customer\Register\Contracts\ValidationFailed;
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
final class CustomerRegisterService
{
    /**
     * Execute registration
     *
     * @CommandHandler(validate=true)
     *
     * @param RegisterCustomer      $command
     * @param KernelContext         $context
     * @param IndexProvider         $indexProvider
     * @param EventSourcingProvider $eventSourcingProvider
     *
     * @return \Generator
     */
    public function handle(
        RegisterCustomer $command,
        KernelContext $context,
        IndexProvider $indexProvider,
        EventSourcingProvider $eventSourcingProvider
    ): \Generator
    {
        if(false === $context->isValid())
        {
            return yield $context->delivery(ValidationFailed::create($context->traceId(), $context->violations()));
        }

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

        return yield $context->delivery(ValidationFailed::duplicatePhoneNumber($context->traceId()));
    }

    /**
     * @EventListener()
     *
     * @param CustomerAggregateCreated $event
     * @param KernelContext            $context
     *
     * @return Promise
     */
    public function whenCustomerAggregateCreated(CustomerAggregateCreated $event, KernelContext $context): Promise
    {
        return $context->delivery(CustomerRegistered::create($event->id, $context->traceId()));
    }
}
