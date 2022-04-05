<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Filesystem;

/**
 * Stored document representation
 *
 * @psalm-immutable
 */
final class StoredDocument
{
    /**
     * Document identifier
     *
     * @psalm-readonly
     *
     * @var DocumentId
     */
    public $id;

    /**
     * Document data
     *
     * @psalm-readonly
     *
     * @var Document
     */
    public $data;

    public function __construct(DocumentId $id, Document $data)
    {
        $this->id   = $id;
        $this->data = $data;
    }
}
