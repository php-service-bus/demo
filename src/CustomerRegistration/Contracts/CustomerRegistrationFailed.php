<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\CustomerRegistration\Contracts;

use ServiceBus\Services\Contracts\ExecutionFailedEvent;

/**
 * Some error occured
 *
 * @api
 * @see RegisterCustomer
 *
 * @property-read string $correlationId
 * @property-read string $reason
 */
final class CustomerRegistrationFailed implements ExecutionFailedEvent
{
    /**
     * Request operation id
     *
     * @var string
     */
    public $correlationId;

    /**
     * Error message
     *
     * @var string
     */
    public $reason;

    /**
     * @inheritDoc
     */
    public static function create(string $correlationId, string $errorMessage): ExecutionFailedEvent
    {
        return new self($correlationId, $errorMessage);
    }

    /**
     * @inheritdoc
     */
    public function correlationId(): string
    {
        return $this->correlationId;
    }

    /**
     * @inheritdoc
     */
    public function errorMessage(): string
    {
        return $this->reason;
    }

    /**
     * @param string $correlationId
     * @param string $errorMessage
     */
    private function __construct(string $correlationId, string $errorMessage)
    {
        $this->correlationId = $correlationId;
        $this->reason = $errorMessage;
    }
}
