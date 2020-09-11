<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Add\Contract;

use ServiceBus\Services\Contracts\ExecutionFailedEvent;

/**
 * Some error occured
 *
 * @api
 * @see AddVehicle
 *
 * @psalm-immutable
 */
final class AddVehicleFailed implements ExecutionFailedEvent
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

    public static function create(string $correlationId, string $errorMessage): ExecutionFailedEvent
    {
        return new self($correlationId, $errorMessage);
    }

    public function __construct(string $correlationId, string $reason)
    {
        $this->correlationId = $correlationId;
        $this->reason        = $reason;
    }

    public function correlationId(): string
    {
        return $this->correlationId;
    }

    public function errorMessage(): string
    {
        return $this->reason;
    }
}
