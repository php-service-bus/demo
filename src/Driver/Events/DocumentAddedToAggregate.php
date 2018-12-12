<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Events;

use App\DriverDocument\Data\DriverDocumentId;
use App\DriverDocument\Data\DriverDocumentType;
use App\Driver\DriverId;
use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * Document successfully added to aggregate
 *
 * internal event
 */
final class DocumentAddedToAggregate implements Event
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
        $self = new self();

        $self->driverId   = $driverId;
        $self->documentId = $documentId;
        $self->type       = $type;
        $self->imagePath  = $imagePath;

        return $self;
    }

    private function __construct()
    {

    }
}
