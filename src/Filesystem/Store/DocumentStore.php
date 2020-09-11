<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Filesystem\Store;

use Amp\Promise;
use App\Filesystem\Document;
use App\Filesystem\DocumentId;

/**
 *
 */
interface DocumentStore
{
    /**
     * Store document
     *
     * @return Promise<\App\Filesystem\DocumentId>
     *
     * @throws \App\Filesystem\Exceptions\SaveFileFailed
     */
    public function store(Document $document): Promise;

    /**
     * Obtain stored document
     *
     * @return Promise<\App\Filesystem\StoredDocument|null>
     *
     * @throws \App\Filesystem\Exceptions\ObtainFileFailed
     */
    public function obtain(DocumentId $id): Promise;

    /**
     * Remove stored document
     *
     * @return Promise<void>
     */
    public function remove(DocumentId $id): Promise;
}
