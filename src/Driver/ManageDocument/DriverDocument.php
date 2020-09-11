<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument;

use App\Filesystem\DocumentId;

/**
 * Attached document
 */
final class DriverDocument
{
    /**
     * @var DocumentId
     */
    public $id;

    /**
     * @var DriverDocumentType
     */
    public $type;

    /**
     * @var DriverDocumentStatus
     */
    public $status;

    public function __construct(DocumentId $id, DriverDocumentType $type, DriverDocumentStatus $status)
    {
        $this->id     = $id;
        $this->type   = $type;
        $this->status = $status;
    }
}
