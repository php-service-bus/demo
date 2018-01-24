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
use Desperado\Saga\Service\SagaService;
use Desperado\ServiceBus\Annotations;
use Desperado\ServiceBusDemo\Application\ApplicationContext;
use Desperado\ServiceBusDemo\Customer\Command as CustomerCommands;
use Desperado\ServiceBusDemo\Customer\CustomerAggregate;
use Desperado\ServiceBusDemo\Customer\Event as CustomerEvents;
use Desperado\ServiceBusDemo\Customer\Identity as CustomerIdentities;
use Desperado\ServiceBus\Services\Handlers\Exceptions\UnfulfilledPromiseData;
use Desperado\ServiceBus\Services\ServiceInterface;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

/**
 * @Annotations\Service()
 */
class RegisterCustomerService implements ServiceInterface
{
    private const EMAIL_INDEX_NAME = 'UserEmails';

    /**
     * @Annotations\CommandHandler
     *
     * @param CustomerCommands\RegisterCustomerCommand $command
     * @param ApplicationContext                       $context
     * @param AggregateManager                         $aggregateManager
     * @param  Indexer                                 $indexer
     *
     * @return PromiseInterface
     */
    public function executeRegisterCustomerCommand(
        CustomerCommands\RegisterCustomerCommand $command,
        ApplicationContext $context,
        AggregateManager $aggregateManager,
        Indexer $indexer
    ): PromiseInterface
    {
        return new Promise(
            function() use ($command, $context, $aggregateManager, $indexer)
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
        );
    }

    /**
     * @Annotations\ErrorHandler(
     *     message="Desperado\ServiceBusDemo\Customer\Command\RegisterCustomerCommand",
     *     type="Exception",
     *     loggerChannel="registrationFail"
     * )
     *
     * @param UnfulfilledPromiseData $unfulfilledPromiseData
     *
     * @return PromiseInterface
     */
    public function failedRegisterCustomerCommand(UnfulfilledPromiseData $unfulfilledPromiseData): PromiseInterface
    {
        return new Promise(
            function() use ($unfulfilledPromiseData)
            {
                /** @var CustomerCommands\RegisterCustomerCommand $registerCommand */
                $registerCommand = $unfulfilledPromiseData->getMessage();

                $unfulfilledPromiseData
                    ->getContext()
                    ->delivery(
                        CustomerEvents\FailedRegistrationEvent::create([
                            'requestId' => $registerCommand->getRequestId(),
                            'reason'    => $unfulfilledPromiseData->getThrowable()->getMessage()
                        ])
                    );
            }
        );
    }

    /**
     * @Annotations\EventHandler()
     *
     * @param CustomerEvents\CustomerRegisteredEvent $event
     * @param ApplicationContext                     $context
     * @param SagaService                            $sagaService
     *
     * @return PromiseInterface
     *
     * @throws \Throwable
     */
    public function whenCustomerRegisteredEvent(
        CustomerEvents\CustomerRegisteredEvent $event,
        ApplicationContext $context,
        SagaService $sagaService
    ): PromiseInterface
    {
        return $sagaService->startSaga(
            new CustomerIdentities\CustomerVerificationSagaIdentifier($event->getRequestId()),
            CustomerCommands\StartVerificationSagaCommand::create(['customerIdentifier' => $event->getIdentifier()])
        );
    }
}
