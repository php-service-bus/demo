<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration\Contracts;

use Desperado\ServiceBus\Services\Contracts\ExecutionFailedEvent;

/**
 * Some error occured
 *
 * @api
 * @see RegisterCustomer
 */
final class CustomerRegistrationFailed implements ExecutionFailedEvent
{
    /**
     * Registration request Id
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
     * @inheritdoc
     */
    public static function create(string $correlationId, string $errorMessage): ExecutionFailedEvent
    {
        $self = new self();

        $self->correlationId = $correlationId;
        $self->reason        = $errorMessage;

        return $self;
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
}
