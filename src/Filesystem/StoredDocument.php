<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Filesystem;

/**
 * Stored document representation
 */
final class StoredDocument
{
    /**
     * Document identifier
     *
     * @var DocumentId
     */
    public $id;

    /**
     * Document data
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
