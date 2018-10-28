<?php

/**
 * PHP Telegram Bot Api implementation
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace ServiceBusDemo\ManageCustomer;

use Amp\Promise;
use Desperado\ServiceBus\Application\KernelContext;
use Desperado\ServiceBus\EventSourcingProvider;
use Desperado\ServiceBus\Services\Annotations\CommandHandler;
use Desperado\ServiceBus\Services\Annotations\EventListener;
use ServiceBusDemo\Customer\Contract\CustomerNotExists;
use ServiceBusDemo\Customer\Customer;
use ServiceBusDemo\Customer\CustomerId;
use ServiceBusDemo\Customer\Events\FullNameChanged;
use ServiceBusDemo\ManageCustomer\Contract\Rename\CustomerFullNameChanged;
use ServiceBusDemo\ManageCustomer\Contract\Rename\RenameCustomer;
use ServiceBusDemo\ManageCustomer\Contract\Rename\RenameCustomerValidationFailed;

/**
 *
 */
final class ManageCustomerService
{
    /**
     * @CommandHandler()
     *
     * @param RenameCustomer        $command
     * @param KernelContext         $context
     * @param EventSourcingProvider $provider
     *
     * @return \Generator
     */
    public function rename(
        RenameCustomer $command,
        KernelContext $context,
        EventSourcingProvider $provider
    ): \Generator
    {
        $context->logContextMessage('Customer name change request received', [
            'customerId' => $command->customerId()
        ]);

        if(false === $context->isValid())
        {
            return yield $context->delivery(
                RenameCustomerValidationFailed::create($context->violations())
            );
        }

        /** @var Customer|null $customer */
        $customer = yield $provider->load(new CustomerId($command->customerId()));

        if(null === $customer)
        {
            return yield $context->delivery(CustomerNotExists::create($command->customerId()));
        }

        $customer->rename($command->fullName());

        yield $provider->save($customer, $context);
    }

    /**
     * @EventListener()
     *
     * @param FullNameChanged $event
     * @param KernelContext   $context
     *
     * @return Promise
     */
    public function whenFullNameChanged(FullNameChanged $event, KernelContext $context): Promise
    {
        return $context->delivery(
            CustomerFullNameChanged::create($event->id(), $event->newFullName())
        );
    }

    /**
     * @EventListener()
     *
     * @param CustomerFullNameChanged $event
     * @param KernelContext           $context
     *
     * @return void
     */
    public function whenCustomerFullNameChanged(CustomerFullNameChanged $event, KernelContext $context): void
    {
        $context->logContextMessage(
            'Customer with id "{customerId}" successful renamed', ['customerId' => (string) $event->id()]
        );
    }
}
