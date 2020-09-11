<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument\Add\Contract;

use ServiceBus\Services\Contracts\ExecutionFailedEvent;

/**
 * Error while adding a document
 *
 * @api
 * @see AddDriverDocument
 *
 * @psalm-immutable
 */
final class AddDriverDocumentFailure implements ExecutionFailedEvent
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
