<?php

/**
 * PHP Service Bus (CQS implementation)
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace Desperado\ServiceBusDemo\Customer\Services;

use Desperado\Domain\Uuid;
use Desperado\EventSourcing\Aggregates\AggregateManager;
use Desperado\EventSourcing\Indexes\Indexer;
use Desperado\ServiceBus\Annotations;
use Desperado\ServiceBus\SagaProvider;
use Desperado\ServiceBus\ServiceInterface;
use Desperado\ServiceBusDemo\Application\ApplicationContext;
use Desperado\ServiceBusDemo\Customer\Command as CustomerCommands;
use Desperado\ServiceBusDemo\Customer\CustomerAggregate;
use Desperado\ServiceBusDemo\Customer\CustomerVerificationSaga;
use Desperado\ServiceBusDemo\Customer\Event as CustomerEvents;
use Desperado\ServiceBusDemo\Customer\Identifier as CustomerIdentities;

/**
 * @Annotations\Services\Service()
 */
class RegisterCustomerService implements ServiceInterface
{
    private const EMAIL_INDEX_NAME = 'UserEmails';

    /**
     * @Annotations\Services\CommandHandler
     *
     * @param CustomerCommands\RegisterCustomerCommand $command
     * @param ApplicationContext                       $context
     * @param AggregateManager                         $aggregateManager
     * @param  Indexer                                 $indexer
     *
     * @return void
     */
    public function executeRegisterCustomerCommand(
        CustomerCommands\RegisterCustomerCommand $command,
        ApplicationContext $context,
        AggregateManager $aggregateManager,
        Indexer $indexer
    ): void
    {
        /** new customer */
        if(false === $indexer->has(self::EMAIL_INDEX_NAME, $command->getEmail()))
        {
            /** @var CustomerIdentities\CustomerAggregateIdentifier $customerIdentifier */
            $customerIdentifier = new CustomerIdentities\CustomerAggregateIdentifier(
                Uuid::v4(),
                CustomerAggregate::class
            );

            $aggregate = new CustomerAggregate($customerIdentifier);
            $aggregate->fill($command);

            $aggregateManager->persist($aggregate);

            $indexer->store(self::EMAIL_INDEX_NAME, $command->getEmail(), $aggregate->getIdentityAsString());
        }
        else
        {
            $context->delivery(
                CustomerEvents\CustomerAlreadyExistsEvent::create([
                    'requestId'  => $command->getRequestId(),
                    'identifier' => $indexer->get(self::EMAIL_INDEX_NAME, $command->getEmail())
                ])
            );
        }
    }

    /**
     * @Annotations\Services\EventHandler()
     *
     * @param CustomerEvents\CustomerRegisteredEvent $event
     * @param ApplicationContext                     $context
     * @param SagaProvider                           $sagaProvider
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function whenCustomerRegisteredEvent(
        CustomerEvents\CustomerRegisteredEvent $event,
        ApplicationContext $context,
        SagaProvider $sagaProvider
    ): void
    {
        unset($context);

        $sagaProvider->start(
            new CustomerIdentities\CustomerVerificationSagaIdentifier($event->getRequestId(), CustomerVerificationSaga::class),
            CustomerCommands\StartVerificationSagaCommand::create(['customerIdentifier' => $event->getIdentifier()])
        );
    }
}
