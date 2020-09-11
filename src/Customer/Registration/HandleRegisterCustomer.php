<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration;

use Amp\Promise;
use App\Customer\Customer;
use App\Customer\CustomerContacts;
use App\Customer\CustomerFullName;
use App\Customer\Registration\Contract\RegisterCustomer;
use App\Customer\Registration\Contract\RegisterCustomerValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\EventSourcing\EventSourcingProvider;
use ServiceBus\EventSourcing\Indexes\IndexKey;
use ServiceBus\EventSourcing\Indexes\IndexValue;
use ServiceBus\EventSourcing\IndexProvider;
use ServiceBus\Services\Annotations\CommandHandler;
use function Amp\call;

/**
 * Execute customer registration
 */
final class HandleRegisterCustomer
{
    /**
     * @CommandHandler(
     *     description="New customer registration",
     *     validate=true,
     *     defaultValidationFailedEvent="App\Customer\Registration\Contract\RegisterCustomerValidationFailed",
     *     defaultThrowableEvent="App\Customer\Registration\Contract\CustomerRegistrationFailed"
     * )
     */
    public function handle(
        RegisterCustomer $command,
        ServiceBusContext $context,
        IndexProvider $indexProvider,
        EventSourcingProvider $eventSourcingProvider
    ): Promise {
        return call(
            static function () use ($command, $context, $indexProvider, $eventSourcingProvider): \Generator
            {
                $customer = Customer::register(
                    new CustomerFullName($command->firstName, $command->lastName),
                    new CustomerContacts($command->phone, $command->email)
                );

                /** @var bool $canBeRegistered */
                $canBeRegistered = yield $indexProvider->add(
                    new IndexKey('customer', $command->phone),
                    new IndexValue($customer->id()->toString())
                );

                /** Check the uniqueness of the phone number */
                if ($canBeRegistered)
                {
                    return yield $eventSourcingProvider->save($customer, $context);
                }

                return yield $context->delivery(
                    RegisterCustomerValidationFailed::duplicatePhoneNumber($context->traceId())
                );
            }
        );
    }
}
