<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) demo
 * Supports Saga pattern and Event Sourcing
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBusDemo\Customer\Event;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * @see RegisterCustomer
 */
final class CustomerValidationFailed implements Event
{
    /**
     * Trace ID
     *
     * @var string
     */
    private $operationId;

    /**
     * Violations list
     *
     * @psalm-var array<string, array<int, string>>
     *
     * @var array
     */
    private $violations;

    /**
     * @param string                            $operationId
     * @param array<string, array<int, string>> $violations
     *
     * @return self
     */
    public static function create(string $operationId, array $violations): self
    {
        $self = new self();

        $self->operationId = $operationId;
        $self->violations  = $violations;

        return $self;
    }

    private function __construct()
    {

    }
}
