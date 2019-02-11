<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverVehicle\Contract\Manage;

use ServiceBus\Services\Contracts\ExecutionFailedEvent;

/**
 * Some error occured
 *
 * @api
 * @see AddDriverVehicle
 *
 * @property-read string $correlationId
 * @property-read string $reason
 */
final class AddDriverVehicleFailed implements ExecutionFailedEvent
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
     * @param string $correlationId
     * @param string $reason
     *
     * @return self
     */
    public static function create(string $correlationId, string $reason): ExecutionFailedEvent
    {
        return new self($correlationId, $reason);
    }

    /**
     * @inheritDoc
     */
    public function correlationId(): string
    {
        return $this->correlationId;
    }

    /**
     * @inheritDoc
     */
    public function errorMessage(): string
    {
        return $this->reason;
    }

    /**
     * @param string $correlationId
     * @param string $reason
     */
    private function __construct(string $correlationId, string $reason)
    {
        $this->correlationId = $correlationId;
        $this->reason        = $reason;
    }
}
