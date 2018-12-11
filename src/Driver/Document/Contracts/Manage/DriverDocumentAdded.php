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

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * Document successfully added
 *
 * @api
 * @see AddDriverDocument
 */
final class DriverDocumentAdded implements Event
{
    /**
     * Registration request Id
     *
     * @var string
     */
    public $correlationId;

    /**
     * Driver id
     *
     * @var string
     */
    public $driverId;

    /**
     * Uploaded document id
     *
     * @var string
     */
    public $documentId;

    /**
     * @param string $correlationId
     * @param string $driverId
     * @param string $documentId
     *
     * @return self
     */
    public static function create(string $correlationId, string $driverId, string $documentId): self
    {
        $self = new self();

        $self->correlationId = $correlationId;
        $self->driverId      = $driverId;
        $self->documentId    = $documentId;

        return $self;
    }

    private function __construct()
    {

    }
}
