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
 * Document entry
 *
 * @psalm-immutable
 */
final class Document
{
    /**
     * Original file name
     *
     * @var string
     */
    public $fileName;

    /**
     * Document metadata
     *
     * @var DocumentMetadata
     */
    public $metadata;

    /**
     * Binary file data
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
