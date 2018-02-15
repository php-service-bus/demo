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
use Desperado\ServiceBus\ServiceInterface;
use Desperado\ServiceBusDemo\Application\ApplicationContext;
use Desperado\ServiceBusDemo\Customer\Identifier\CustomerAggregateIdentifier;
use Desperado\ServiceBusDemo\Customer\Command as CustomerCommands;
use Desperado\ServiceBusDemo\Customer\CustomerAggregate;
use Desperado\ServiceBusDemo\Customer\Event as CustomerEvents;

/**
 * @Annotations\Services\Service(
 *     loggerChannel="manageCustomers"
 * )
 */
class ManageCustomerService implements ServiceInterface
{
    /**
     * @Annotations\Services\CommandHandler()
     *
     * @param CustomerCommands\ActivateCustomerCommand $command
     * @param ApplicationContext                       $context
     * @param AggregateManager                         $aggregateManager
     *
     * @return void
     */
    public function executeActivateCustomerCommand(
        CustomerCommands\ActivateCustomerCommand $command,
        ApplicationContext $context,
        AggregateManager $aggregateManager
    ): void
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
    }
}
