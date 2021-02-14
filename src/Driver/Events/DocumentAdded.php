<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Events;

use App\Driver\DriverId;
use App\Driver\ManageDocument\DriverDocumentType;
use App\Filesystem\DocumentId;

/**
 * Document successfully added to aggregate
 *
 * @internal
 *
 * @psalm-immutable
 */
final class DocumentAdded
{
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

    /**
     * Document type
     *
     * @psalm-readonly
     *
     * @var DriverDocumentType
     */
    public $type;

    public function __construct(DriverId $driverId, DocumentId $documentId, DriverDocumentType $type)
    {
        $this->driverId   = clone $driverId;
        $this->documentId = clone $documentId;
        $this->type       = clone $type;
    }
}
