<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument\Add\Contract;

use App\Driver\DriverId;
use App\Filesystem\DocumentId;

/**
 * Document successfully added
 *
 * @api
 * @see AddDriverDocument
 *
 * @psalm-immutable
 */
final class DriverDocumentAdded
{
    /**
     * Request operation id
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $correlationId;

    /**
     * Driver id
     *
     * @psalm-readonly
     *
     * @var DriverId
     */
    public $driverId;

    /**
     * Uploaded document id
     *
     * @psalm-readonly
     *
     * @var DocumentId
     */
    public $documentId;

    public function __construct(string $correlationId, DriverId $driverId, DocumentId $documentId)
    {
        $this->correlationId = $correlationId;
        $this->driverId      = clone $driverId;
        $this->documentId    = clone $documentId;
    }
}
