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

use Desperado\EventSourcing\Aggregates\AggregateManager;
use Desperado\ServiceBus\Annotations;
use Desperado\ServiceBusDemo\Application\ApplicationContext;
use Desperado\ServiceBusDemo\Customer\Identity\CustomerAggregateIdentifier;
use Desperado\ServiceBus\Services\ServiceInterface;
use Desperado\ServiceBusDemo\Customer\Command as CustomerCommands;
use Desperado\ServiceBusDemo\Customer\CustomerAggregate;
use Desperado\ServiceBusDemo\Customer\Event as CustomerEvents;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

/**
 * @Annotations\Service(
 *     loggerChannel="manageCustomers"
 * )
 */
class ManageCustomerService implements ServiceInterface
{
    /**
     * @Annotations\CommandHandler()
     *
     * @param CustomerCommands\ActivateCustomerCommand $command
     * @param ApplicationContext                       $context
     * @param AggregateManager                         $aggregateManager
     *
     * @return PromiseInterface
     */
    public function executeActivateCustomerCommand(
        CustomerCommands\ActivateCustomerCommand $command,
        ApplicationContext $context,
        AggregateManager $aggregateManager
    ): PromiseInterface
    {
        return new Promise(
            function() use ($context, $command, $aggregateManager)
            {
                $identifier = new CustomerAggregateIdentifier(
                    $command->getIdentifier(),
                    CustomerAggregate::class
                );

                /** @var CustomerAggregate $aggregate */
                $aggregate = $aggregateManager->obtainAggregate($identifier);

                if(null !== $aggregate)
                {
                    $aggregate->activate($command);

                    return;
                }

                $context->delivery(
                    CustomerEvents\CustomerAggregateNotFoundEvent::create([
                        'requestId'  => $command->getRequestId(),
                        'identifier' => $command->getIdentifier()
                    ])
                );

                return;
            }
        );
    }
}
