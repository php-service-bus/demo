<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument\Contracts\Manage;

/**
 * Document successfully added
 *
 * @api
 * @see AddDriverDocument
 *
 * @property-read string $correlationId
 * @property-read string $driverId
 * @property-read string $documentId
 */
final class DriverDocumentAdded
{
    /**
     * Request operation id
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
        return new self($correlationId, $driverId, $documentId);
    }

    /**
     * @param string $correlationId
     * @param string $driverId
     * @param string $documentId
     */
    private function __construct(string $correlationId, string $driverId, string $documentId)
    {
        $this->correlationId = $correlationId;
        $this->driverId      = $driverId;
        $this->documentId    = $documentId;
    }
}
