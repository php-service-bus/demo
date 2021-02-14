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
 * Document metadata
 */
final class DocumentMetadata
{
    /**
     * File extension
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $extension;

    /**
     * Document media type
     *
     * @psalm-readonly
     *
     * @var DocumentMimeType
     */
    public $mimeType;

    public function __construct(string $extension, DocumentMimeType $mimeType)
    {
        $this->extension = $extension;
        $this->mimeType  = $mimeType;
    }
}
