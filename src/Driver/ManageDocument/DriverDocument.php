<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver\ManageDocument;

use App\Filesystem\DocumentId;

/**
 * Attached document
 *
 * @psalm-immutable
 */
final class DriverDocument
{
    /**
     * @psalm-readonly
     *
     * @var DocumentId
     */
    public $id;

    /**
     * @psalm-readonly
     *
     * @var DriverDocumentType
     */
    public $type;

    /**
     * @psalm-readonly
     *
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
