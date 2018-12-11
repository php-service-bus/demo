<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Document\Contracts\Manage;

use Desperado\ServiceBus\Services\Contracts\ExecutionFailedEvent;

/**
 * Error while adding a document
 *
 * @api
 * @see AddDriverDocument
 */
final class AddDriverDocumentFailure implements ExecutionFailedEvent
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
     * @param string $correlationId
     * @param string $reason
     *
     * @return self
     */
    public static function create(string $correlationId, string $reason): ExecutionFailedEvent
    {
        $self                = new self();
        $self->correlationId = $correlationId;
        $self->reason        = $reason;

        return $self;
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

    private function __construct()
    {

    }

}
