<?php

/**
 * PHP Service Bus (CQS implementation)
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace Desperado\ServiceBusDemo\Application;

use Desperado\EventSourcing\Aggregates\AggregateManager;
use Desperado\ServiceBus\KernelEvents\MessageProcessingCompletedEvent;

/**
 * Listener, which will be called to save changes to the aggregates
 */
class CommitAggregatesListener
{
    /**
     * Aggregate manager
     *
     * @var AggregateManager
     */
    private $aggregateManager;

    /**
     * @param AggregateManager $aggregateManager
     */
    public function __construct(AggregateManager $aggregateManager)
    {
        $this->aggregateManager = $aggregateManager;
    }

    /**
     * Message execution finished
     *
     * @param MessageProcessingCompletedEvent $event
     *
     * @return void
     */
    public function onComplete(MessageProcessingCompletedEvent $event): void
    {
        $this->aggregateManager->flush($event->getExecutionContext());
    }
}
