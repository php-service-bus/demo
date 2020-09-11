<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument\Add;

use Amp\Promise;
use App\Driver\Driver;
use App\Driver\DriverId;
use App\Driver\ManageDocument\Add\Contract\AddDriverDocument;
use App\Driver\ManageDocument\Add\Contract\AddDriverDocumentValidationFailed;
use App\Driver\ManageDocument\DriverDocument;
use App\Driver\ManageDocument\DriverDocumentStatus;
use App\Driver\ManageDocument\DriverDocumentType;
use App\Filesystem\Document;
use App\Filesystem\DocumentMetadata;
use App\Filesystem\DocumentMimeType;
use App\Filesystem\Store\DocumentStore;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\EventSourcing\EventSourcingProvider;
use ServiceBus\Services\Annotations\CommandHandler;
use function Amp\call;

/**
 * Add new driver document
 */
final class HandleAddDriverDocument
{
    /**
     * @CommandHandler(
     *     description="Add document to driver",
     *     validate=true,
     *     defaultValidationFailedEvent="App\Driver\ManageDocument\Add\Contract\AddDriverDocumentValidationFailed",
     *     defaultThrowableEvent="App\Driver\ManageDocument\Add\Contract\AddDriverDocumentFailure"
     * )
     */
    public function handle(
        AddDriverDocument $command,
        ServiceBusContext $context,
        EventSourcingProvider $eventSourcingProvider,
        DocumentStore $documentStore
    ): Promise {
        return call(
            function () use ($command, $context, $eventSourcingProvider, $documentStore): \Generator
            {
                /** @var Driver|null $driver */
                $driver = yield $eventSourcingProvider->load(new DriverId($command->driverId));

                if ($driver === null)
                {
                    return yield $context->delivery(
                        AddDriverDocumentValidationFailed::driverNotFound($context->traceId())
                    );
                }

                $document   = $this->createDocument($command);
                $documentId = yield $documentStore->store($document);

                $driver->attachDocument(
                    new DriverDocument(
                        $documentId,
                        DriverDocumentType::create($command->type),
                        DriverDocumentStatus::moderation()
                    )
                );

                return yield $eventSourcingProvider->save($driver, $context);
            }
        );
    }

    /**
     * @todo
     *
     * @throws \InvalidArgumentException
     */
    private function createDocument(AddDriverDocument $command): Document
    {
        $fileNameParts = \explode('.', $command->filename);
        $mimeParts     = \explode('/', $command->mimeType);

        if (\count($mimeParts) !== 2)
        {
            throw new \InvalidArgumentException('Incorrect mime type');
        }

        if (\count($fileNameParts) !== 2)
        {
            throw new \InvalidArgumentException('Filename must contain extension');
        }

        return new Document(
            $fileNameParts[0],
            new DocumentMetadata($fileNameParts[1], new DocumentMimeType($mimeParts[0], $mimeParts[1])),
            (string) \base64_decode($command->payload)
        );
    }
}
