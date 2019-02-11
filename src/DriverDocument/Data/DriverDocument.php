<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument\Data;

use function ServiceBus\Common\datetimeInstantiator;

/**
 * @property-read DriverDocumentId     $id
 * @property-read string               $imagePath
 * @property-read DriverDocumentType   $type
 * @property-read DriverDocumentStatus $status
 * @property-read \DateTimeImmutable   $createdAt
 */
final class DriverDocument
{
    /**
     * Document identifier
     *
     * @var DriverDocumentId
     */
    public $id;

    /**
     * Absolute path to the loaded document
     *
     * @var string
     */
    public $imagePath;

    /**
     * Document type
     *
     * @var DriverDocumentType
     */
    public $type;

    /**
     * Document status
     *
     * @var DriverDocumentStatus
     */
    public $status;

    /**
     * Creation date
     *
     * @var \DateTimeImmutable
     */
    public $createdAt;

    /**
     * @param DriverDocumentId   $id
     * @param string             $imagePath
     * @param DriverDocumentType $type
     *
     * @return self
     */
    public static function create(DriverDocumentId $id, string $imagePath, DriverDocumentType $type): self
    {
        return new self($id, $imagePath, $type);
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param DriverDocumentId   $id
     * @param string             $imagePath
     * @param DriverDocumentType $type
     */
    private function __construct(DriverDocumentId $id, string $imagePath, DriverDocumentType $type)
    {
        /**
         * @noinspection PhpUnhandledExceptionInspection
         * @var \DateTimeImmutable $currentDate
         */
        $currentDate = datetimeInstantiator('NOW');

        $this->id        = $id;
        $this->imagePath = $imagePath;
        $this->type      = $type;
        $this->status    = DriverDocumentStatus::moderation();
        $this->createdAt = $currentDate;
    }
}
