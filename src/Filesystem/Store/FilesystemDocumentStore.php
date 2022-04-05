<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Filesystem\Store;

use Amp\Promise;
use App\Filesystem\Document;
use App\Filesystem\DocumentId;
use App\Filesystem\DocumentMetadata;
use App\Filesystem\DocumentMimeType;
use App\Filesystem\Exceptions\ObtainFileFailed;
use App\Filesystem\Exceptions\SaveFileFailed;
use App\Filesystem\StoredDocument;
use ServiceBus\Storage\Common\DatabaseAdapter;
use function Amp\call;
use function Amp\File\createDirectoryRecursively;
use function Amp\File\deleteFile;
use function Amp\File\exists;
use function Amp\File\write;
use function ServiceBus\Common\now;
use function ServiceBus\Common\uuid;
use function ServiceBus\Storage\Sql\deleteQuery;
use function ServiceBus\Storage\Sql\equalsCriteria;
use function ServiceBus\Storage\Sql\fetchOne;
use function ServiceBus\Storage\Sql\find;
use function ServiceBus\Storage\Sql\insertQuery;

use function Amp\File\read;

/**
 *
 */
final class FilesystemDocumentStore implements DocumentStore
{
    /**
     * @var DatabaseAdapter
     */
    private $store;

    /**
     * @var string
     */
    private $toDirectory;

    public function __construct(DatabaseAdapter $store, string $toDirectory)
    {
        $this->store       = $store;
        $this->toDirectory = $toDirectory;
    }

    public function store(Document $document): Promise
    {
        return call(
            function() use ($document): \Generator
            {
                try
                {
                    $documentId    = new DocumentId(uuid());
                    $directoryPath = $this->generateDirectoryPath();
                    $filePath      = \sprintf(
                        '%s/%s.%s',
                        $directoryPath,
                        $document->fileName,
                        $document->metadata->extension
                    );

                    try
                    {
                        yield $this->storeReference($documentId, $document, $filePath);
                        yield $this->saveFile($document->payload, $directoryPath, $filePath);

                        return $documentId;
                    }
                    catch(\Throwable $throwable)
                    {
                        yield $this->remove($documentId);

                        throw $throwable;
                    }
                }
                catch(\Throwable $throwable)
                {
                    throw new SaveFileFailed('Unable to store file', (int) $throwable->getCode(), $throwable);
                }
            }
        );
    }

    public function obtain(DocumentId $id): Promise
    {
        return call(
            function() use ($id): \Generator
            {
                $result = yield $this->findReference($id);

                if($result !== null)
                {
                    /** @var string|null $payload */
                    $payload = yield $this->obtainFilePayload($result['file_path']);

                    if($payload !== null)
                    {
                        return new StoredDocument(
                            new DocumentId($result['id']),
                            new Document(
                                $result['file_name'],
                                new DocumentMetadata(
                                    $result['extension'],
                                    new DocumentMimeType($result['mime_type_base'], $result['mime_type_sub'])
                                ),
                                $payload
                            )
                        );
                    }

                    throw new ObtainFileFailed(
                        \sprintf('Could not find saved file `%s`', $result['file_path'])
                    );
                }

                return null;
            }
        );
    }

    public function remove(DocumentId $id): Promise
    {
        return call(
            function() use ($id): \Generator
            {
                try
                {
                    /** @var string|null $filePath */
                    $filePath = yield $this->removeReference($id);

                    if($filePath !== null)
                    {
                        yield $this->removeFile($filePath);
                    }
                }
                catch(\Throwable)
                {
                    /** Not interests */
                }
            }
        );
    }

    /**
     * @return Promise<void>
     */
    private function storeReference(DocumentId $id, Document $document, string $filePath): Promise
    {
        return call(
            function() use ($id, $document, $filePath): \Generator
            {
                $query = insertQuery('document_store', [
                    'id'             => $id->toString(),
                    'file_name'      => $document->fileName,
                    'extension'      => $document->metadata->extension,
                    'mime_type_base' => $document->metadata->mimeType->base,
                    'mime_type_sub'  => $document->metadata->mimeType->subType,
                    'file_path'      => $filePath,
                    'created_at'     => now()->format('Y-m-d H:i:s.u')
                ])->compile();

                /** @psalm-suppress MixedArgumentTypeCoercion */
                yield $this->store->execute($query->sql(), $query->params());
            }
        );
    }

    /**
     * @return Promise<void>
     */
    private function saveFile(string $payload, string $directoryPath, string $filePath): Promise
    {
        return call(
            static function() use ($payload, $directoryPath, $filePath): \Generator
            {
                /** @var bool $directoryExists */
                $directoryExists = yield exists($directoryPath);

                if($directoryExists === false)
                {
                    yield createDirectoryRecursively($directoryPath);
                }

                yield write($filePath, $payload);
            }
        );
    }

    /**
     * @return Promise<string|null>
     */
    private function removeReference(DocumentId $id): Promise
    {
        return call(
            function() use ($id): \Generator
            {
                $result = yield $this->findReference($id);

                if($result !== null)
                {
                    yield $this->removeFile($result['file_path']);

                    $query = deleteQuery('document_store')
                        ->where(equalsCriteria('id', $id->toString()))
                        ->compile();

                    /** @psalm-suppress MixedArgumentTypeCoercion */
                    yield $this->store->execute($query->sql(), $query->params());

                    return $result['file_path'];
                }
            }
        );
    }

    /**
     * @return Promise<void>
     */
    private function removeFile(string $filePath): Promise
    {
        return call(
            static function() use ($filePath): \Generator
            {
                try
                {
                    yield deleteFile($filePath);
                }
                catch(\Throwable)
                {
                    /** Not interests */
                }
            }
        );
    }

    /**
     * @return Promise<array{
     *    id: string,
     *    file_name: string,
     *    file_path: string,
     *    extension: string,
     *    mime_type_base: string,
     *    mime_type_sub: string
     * }|null>
     */
    private function findReference(DocumentId $id): Promise
    {
        return call(
            function() use ($id): \Generator
            {
                $resultSet = yield find(
                    $this->store,
                    'document_store',
                    [equalsCriteria('id', $id->toString())]
                );

                /**
                 * @psalm-var    array{
                 *    id: string,
                 *    file_name: string,
                 *    file_path: string,
                 *    extension: string,
                 *    mime_type_base: string,
                 *    mime_type_sub: string
                 * }|null $result
                 *
                 * @noinspection OneTimeUseVariablesInspection
                 * @noinspection PhpUnnecessaryLocalVariableInspection
                 */
                $result = yield fetchOne($resultSet);

                return $result;
            }
        );
    }

    /**
     * @return Promise<string|null>
     *
     * @throws \App\Filesystem\Exceptions\ObtainFileFailed
     */
    private function obtainFilePayload(string $filePath): Promise
    {
        return call(
            static function() use ($filePath): \Generator
            {
                try
                {
                    /** @var bool $fileExists */
                    $fileExists = yield exists($filePath);

                    if($fileExists)
                    {
                        /** @var string $payload */
                        $payload = yield read($filePath);

                        return $payload;
                    }

                    return null;
                }
                catch(\Throwable $throwable)
                {
                    throw new ObtainFileFailed(
                        \sprintf('Obtain file `%s` failed', $filePath),
                        (int) $throwable->getCode(),
                        $throwable
                    );
                }
            }
        );
    }

    private function generateDirectoryPath(): string
    {
        return \sprintf(
            '%s/%s/%s',
            $this->toDirectory,
            \date('Y'),
            \date('m')
        );
    }
}
