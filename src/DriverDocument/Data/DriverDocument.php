<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument\Data;

use function Desperado\ServiceBus\Common\datetimeInstantiator;

/**
 *
 */
final class DriverDocument
{
    /**
     * Document identifier
     *
     * @var DriverDocumentId
     */
    private $id;

    /**
     * Absolute path to the loaded document
     *
     * @var string
     */
    private $imagePath;

    /**
     * Document type
     *
     * @var DriverDocumentType
     */
    private $type;

    /**
     * Document status
     *
     * @var DriverDocumentStatus
     */
    private $status;

    /**
     * Creation date
     *
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @param DriverDocumentId     $id
     * @param string               $imagePath
     * @param DriverDocumentType   $type
     */
    public function __construct(DriverDocumentId $id, string $imagePath, DriverDocumentType $type)
    {
        $this->id        = $id;
        $this->imagePath = $imagePath;
        $this->type      = $type;
        $this->status    = DriverDocumentStatus::moderation();
        $this->createdAt = datetimeInstantiator('NOW');
    }

    /**
     * Receive uploaded document id
     *
     * @return DriverDocumentId
     */
    public function id(): DriverDocumentId
    {
        return $this->id;
    }

    /**
     * Receive uploaded image path
     *
     * @return string
     */
    public function imagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * Receive document type
     *
     * @return DriverDocumentType
     */
    public function type(): DriverDocumentType
    {
        return $this->type;
    }

    /**
     * Receive document status
     *
     * @return DriverDocumentStatus
     */
    public function status(): DriverDocumentStatus
    {
        return $this->status;
    }

    /**
     * Receive document creation date
     *
     * @return \DateTimeImmutable
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
