<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) demo
 * Supports Saga pattern and Event Sourcing
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBusDemo\Customer;

use Amp\Promise;
use Desperado\ServiceBus\Application\KernelContext;
use Desperado\ServiceBus\EventSourcingProvider;
use Desperado\ServiceBus\Index\IndexKey;
use Desperado\ServiceBus\Index\IndexValue;
use Desperado\ServiceBus\IndexProvider;
use Desperado\ServiceBus\Services\Annotations\CommandHandler;
use Desperado\ServiceBus\Services\Annotations\EventListener;
use ServiceBusDemo\Customer\Command\RegisterCustomer;
use ServiceBusDemo\Customer\Event\CustomerAlreadyRegistered;
use ServiceBusDemo\Customer\Event\CustomerCreated;
use ServiceBusDemo\Customer\Event\CustomerRegistered;
use ServiceBusDemo\Customer\Event\CustomerValidationFailed;

/**
 * Customer registration service
 */
final class RegisterCustomerService
{
    /**
     * Register customer
     *
     * @CommandHandler(validate=true)
     *
     * @param RegisterCustomer      $command
     * @param KernelContext         $context
     * @param IndexProvider         $indexProvider
     * @param EventSourcingProvider $provider
     *
     * @return \Generator
     */
    public function register(
        RegisterCustomer $command,
        KernelContext $context,
        IndexProvider $indexProvider,
        EventSourcingProvider $provider
    ): \Generator
    {
        /** Validation failed */
        if(false === $context->isValid())
        {
            return yield $context->delivery(
                CustomerValidationFailed::create(
                    $command->operationId(),
                    $context->violations()
                )
            );
        }

        $customer = Customer::create($command);

        $customerContacts = $command->contacts();
        $customerIndexKey = IndexKey::create('customer', $customerContacts->email());

        /** @var bool $canRegisterWithThisEmail */
        $canRegisterWithThisEmail = yield $indexProvider->add($customerIndexKey, IndexValue::create((string ) $customer->id()));

        /** Check the uniqueness of the email address */
        if(false === $canRegisterWithThisEmail)
        {
            return yield $context->delivery(
                CustomerAlreadyRegistered::create(
                    $command->operationId(),
                    $customerContacts->email()
                )
            );
        }

        yield $provider->save($customer, $context);
    }

    /**
     * Customer aggregate created
     *
     * @EventListener()
     *
     * @param CustomerCreated $event
     * @param KernelContext   $context
     *
     * @return Promise
     */
    public function whenCustomerCreated(CustomerCreated $event, KernelContext $context): Promise
    {
        $context->logContextMessage('Customer successful registered', [
            'customerId' => (string) $event->id(),
            'email'      => $event->contacts()->email()
        ]);

        return $context->delivery(
            CustomerRegistered::create(
                $event->traceId(),
                $event->id(),
                $event->contacts()->email()
            )
        );
    }
}
