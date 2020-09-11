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
 * Document metadata
 */
final class DocumentMetadata
{
    /**
     * File extension
     *
     * @var string
     */
    public $extension;

    /**
     * Document media type
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
