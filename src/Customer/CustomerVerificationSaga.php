<?php

/**
 * PHP Service Bus (CQS implementation)
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace Desperado\ServiceBusDemo\Customer;

use Desperado\Domain\Message\AbstractCommand;
use Desperado\ServiceBus\Annotations;
use Desperado\ServiceBus\AbstractSaga;
use Desperado\ServiceBusDemo\Customer\Command as CustomerCommands;
use Desperado\ServiceBusDemo\Customer\Event as CustomerEvents;
use Desperado\ServiceBusDemo\EmailNotifications\Command\SendEmailCommand;
use Desperado\ServiceBusDemo\EmailNotifications\Event as EmailNotificationsEvents;

/**
 * @Annotations\Sagas\Saga(
 *     identifierNamespace="Desperado\ServiceBusDemo\Customer\Identifier\CustomerVerificationSagaIdentifier",
 *     containingIdentifierProperty="requestId",
 *     expireDateModifier="+2 days"
 * )
 */
final class CustomerVerificationSaga extends AbstractSaga
{
    /**
     * Start verification saga
     *
     * @param AbstractCommand $command
     *
     * @return void
     */
    public function start(AbstractCommand $command): void
    {
        /** @var CustomerCommands\StartVerificationSagaCommand $command */

        $this->fire(
            CustomerCommands\SendCustomerVerificationMessageCommand::create([
                'requestId'          => $this->getIdentityAsString(),
                'customerIdentifier' => $command->getCustomerIdentifier()
            ])
        );
    }

    /**
     * @Annotations\Sagas\SagaEventListener()
     *
     * @param Event\CustomerVerificationTokenReceivedEvent $event
     *
     * @return void
     */
    private function onCustomerVerificationTokenReceivedEvent(
        CustomerEvents\CustomerVerificationTokenReceivedEvent $event
    ): void
    {
        $this->fire(
            CustomerCommands\ActivateCustomerCommand::create([
                'requestId'  => $event->getRequestId(),
                'identifier' => $event->getIdentifier()
            ])
        );
    }

    /**
     * @Annotations\Sagas\SagaEventListener()
     *
     * @param Event\CustomerActivatedEvent $event
     *
     * @return void
     */
    private function onCustomerActivatedEvent(CustomerEvents\CustomerActivatedEvent $event): void
    {
        /** Somewhere here we got the settings for sending a message */

        $this->fire(
            SendEmailCommand::create([
                'requestId' => $this->getIdentityAsString(),
                'fromEmail' => 'source@source.com',
                'toEmail'   => 'destination@destination.com',
                'body'      => \str_repeat('x', 51),
                'subject'   => 'Test subject'
            ])
        );
    }

    /**
     * @Annotations\Sagas\SagaEventListener()
     *
     * @param Event\CustomerAggregateNotFoundEvent $event
     *
     * @return void
     */
    private function onCustomerAggregateNotFoundEvent(CustomerEvents\CustomerAggregateNotFoundEvent $event): void
    {
        $this->fail(
            \sprintf('Customer aggregate "%s" not found', $event->getIdentifier())
        );
    }

    /**
     * @Annotations\Sagas\SagaEventListener()
     *
     * @param EmailNotificationsEvents\EmailSentEvent $event
     *
     * @return void
     */
    private function onEmailSentEvent(EmailNotificationsEvents\EmailSentEvent $event): void
    {
        $this->complete('Customer successful registered');
    }
}
