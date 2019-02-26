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

use App\DriverDocument\Data\DriverDocumentId;
use App\DriverDocument\Data\DriverDocumentType;
use App\Driver\DriverId;

/**
 * Document successfully added to aggregate
 *
 * internal event
 *
 * @property-read DriverId           $driverId
 * @property-read DriverDocumentId   $documentId
 * @property-read DriverDocumentType $type
 * @property-read string             $imagePath
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
     * @var DriverDocumentId
     */
    public $documentId;

    /**
     * Document type
     *
     * @var DriverDocumentType
     */
    public $type;

    /**
     * Absolute path to image
     *
     * @var string
     */
    public $imagePath;

    /**
     * @param DriverId           $driverId
     * @param DriverDocumentId   $documentId
     * @param DriverDocumentType $type
     * @param string             $imagePath
     *
     * @return self
     */
    public static function create(
        DriverId $driverId,
        DriverDocumentId $documentId,
        DriverDocumentType $type,
        string $imagePath
    ): self
    {
        return new self($driverId, $documentId, $type, $imagePath);
    }

    /**
     * @param DriverId           $driverId
     * @param DriverDocumentId   $documentId
     * @param DriverDocumentType $type
     * @param string             $imagePath
     */
    private function __construct(DriverId $driverId, DriverDocumentId $documentId, DriverDocumentType $type, string $imagePath)
    {
        $this->driverId   = $driverId;
        $this->documentId = $documentId;
        $this->type       = $type;
        $this->imagePath  = $imagePath;
    }
}
