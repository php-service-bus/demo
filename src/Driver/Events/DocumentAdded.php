<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
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
     * @var DriverId
     */
    public $driverId;

    /**
     * Uploaded document id
     *
     * @var DocumentId
     */
    public $documentId;

    /**
     * Document type
     *
     * @var DriverDocumentType
     */
    public $type;

    public function __construct(DriverId $driverId, DocumentId $documentId, DriverDocumentType $type)
    {
        $this->driverId   = $driverId;
        $this->documentId = clone $documentId;
        $this->type       = clone $type;
    }
}
