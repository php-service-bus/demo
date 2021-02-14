<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Filesystem;

/**
 * Document entry
 *
 * @psalm-immutable
 */
final class Document
{
    /**
     * Original file name
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $fileName;

    /**
     * Document metadata
     *
     * @psalm-readonly
     *
     * @var DocumentMetadata
     */
    public $metadata;

    /**
     * Binary file data
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $payload;

    public function __construct(string $fileName, DocumentMetadata $metadata, string $payload)
    {
        $this->fileName = $fileName;
        $this->metadata = clone $metadata;
        $this->payload  = $payload;
    }
}
