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

namespace App\RegisterCustomer;

use Amp\Promise;
use Desperado\ServiceBus\Application\KernelContext;
use Desperado\ServiceBus\EventSourcingProvider;
use Desperado\ServiceBus\Index\IndexKey;
use Desperado\ServiceBus\Index\IndexValue;
use Desperado\ServiceBus\IndexProvider;
use Desperado\ServiceBus\Services\Annotations\CommandHandler;
use Desperado\ServiceBus\Services\Annotations\EventListener;
use App\Customer\Customer;
use App\Customer\Event\CustomerAggregateCreated;
use App\RegisterCustomer\Contract\Register\CustomerAlreadyRegistered;
use App\RegisterCustomer\Contract\Register\CustomerRegistered;
use App\RegisterCustomer\Contract\Register\CustomerValidationFailed;
use App\RegisterCustomer\Contract\Register\RegisterCustomer;

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
        $context->logContextMessage('User registration with email "{customerEmail}" started', [
            'customerEmail' => $command->contacts()->email()
        ]);

        /** Validation failed */
        if(false === $context->isValid())
        {
            return yield $context->delivery(
                CustomerValidationFailed::create($context->violations())
            );
        }

        $customer = Customer::create($command->fullName(), $command->clearPassword(), $command->contacts());

        $customerContacts = $command->contacts();
        $customerIndexKey = IndexKey::create('customer', $customerContacts->email());

        /** @var bool $canRegisterWithThisEmail */
        $canRegisterWithThisEmail = yield $indexProvider->add(
            $customerIndexKey,
            IndexValue::create((string )
            $customer->id())
        );

        /** Check the uniqueness of the email address */
        if(false === $canRegisterWithThisEmail)
        {
            return yield $context->delivery(
                CustomerAlreadyRegistered::create($customerContacts->email())
            );
        }

        yield $provider->save($customer, $context);
    }

    /**
     * Customer aggregate created
     *
     * @EventListener()
     *
     * @param CustomerAggregateCreated $event
     * @param KernelContext            $context
     *
     * @return Promise
     */
    public function whenCustomerAggregateCreated(CustomerAggregateCreated $event, KernelContext $context): Promise
    {
        $context->logContextMessage('Customer with email "{customerEmail}" successful registered', [
            'customerEmail' => $event->contacts()->email(),
            'customerId'    => (string) $event->id(),
            'email'         => $event->contacts()->email()
        ]);

        return $context->delivery(
            CustomerRegistered::create($event->id(), $event->contacts()->email())
        );
    }

    /**
     * @EventListener()
     *
     * @param CustomerValidationFailed $event
     * @param KernelContext            $context
     *
     * @return void
     */
    public function whenCustomerValidationFailed(CustomerValidationFailed $event, KernelContext $context): void
    {
        $context->logContextMessage('Incorrect user registration parameters', [
            'violations' => $event->violations()
        ]);
    }

    /**
     * @EventListener()
     *
     * @param CustomerAlreadyRegistered $event
     * @param KernelContext             $context
     *
     * @return void
     */
    public function whenCustomerAlreadyRegistered(CustomerAlreadyRegistered $event, KernelContext $context): void
    {
        $context->logContextMessage('Customer with email "{customerEmail}" is already registered', [
            'customerEmail' => $event->email()
        ]);
    }
}
