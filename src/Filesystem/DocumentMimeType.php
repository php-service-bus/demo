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
 * Document media type
 *
 * @psalm-immutable
 */
final class DocumentMimeType
{
    /**
     * @psalm-readonly
     *
     * @var string
     */
    public $base;

    /**
     * @psalm-readonly
     *
     * @var string
     */
    public $subType;

    public function __construct(string $base, string $subType)
    {
        $this->base    = $base;
        $this->subType = $subType;
    }
}
