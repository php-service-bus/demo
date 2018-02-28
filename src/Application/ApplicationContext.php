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

use Desperado\Domain\Transport\Context\OutboundMessageContextInterface;
use Desperado\Infrastructure\Bridge\Logger\LoggerRegistry;
use Desperado\ServiceBus\Application\Context\AbstractExecutionContext;
use Desperado\ServiceBus\Transport\Context\OutboundMessageContext;
use Psr\Log\LoggerInterface;

/**
 * Application-level context
 */
class ApplicationContext extends AbstractExecutionContext
{
    /**
     * Outbound context
     *
     * @var OutboundMessageContext
     */
    private $outboundMessageContext;

    /**
     * @inheritdoc
     */
    final public function getLogger(string $channelName): LoggerInterface
    {
        return LoggerRegistry::getLogger($channelName);
    }

    /**
     * @inheritdoc
     */
    final public function applyOutboundMessageContext(OutboundMessageContextInterface $outboundMessageContext): self
    {
        $self = $this->rebuild();
        $self->outboundMessageContext = $outboundMessageContext;

        return $self;
    }

    /**
     * @inheritdoc
     */
    final public function getOutboundMessageContext(): ?OutboundMessageContextInterface
    {
        return $this->outboundMessageContext;
    }

    /**
     * Recreate context object
     *
     * @return ApplicationContext
     */
    protected function rebuild(): self
    {
        return new self($this->getSchedulerProvider());
    }
}
