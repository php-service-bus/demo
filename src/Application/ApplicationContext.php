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
use Desperado\ServiceBus\MessageProcessor\AbstractExecutionContext;
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
    public function getLogger(string $channelName): LoggerInterface
    {
        return LoggerRegistry::getLogger($channelName);
    }

    /**
     * @inheritdoc
     */
    public function applyOutboundMessageContext(OutboundMessageContextInterface $outboundMessageContext): self
    {
        $self = new self();

        $self->outboundMessageContext = $outboundMessageContext;

        return $self;
    }

    /**
     * @inheritdoc
     */
    public function getOutboundMessageContext(): ?OutboundMessageContextInterface
    {
        return $this->outboundMessageContext;
    }
}
