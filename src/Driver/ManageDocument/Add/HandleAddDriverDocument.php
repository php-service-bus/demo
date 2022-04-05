<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver\ManageDocument\Add;

use Amp\Promise;
use App\Driver\Driver;
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
use ServiceBus\EventSourcing\Exceptions\AggregateNotFound;
use ServiceBus\Services\Attributes\CommandHandler;
use function Amp\call;

/**
 * Add new driver document
 */
final class HandleAddDriverDocument
{
    #[CommandHandler(
        description: 'Add document to driver',
        validationEnabled: true
    )]
    public function handle(
        AddDriverDocument     $command,
        ServiceBusContext     $context,
        EventSourcingProvider $eventSourcingProvider,
        DocumentStore         $documentStore
    ): Promise {
        return call(
            function () use ($command, $context, $eventSourcingProvider, $documentStore): \Generator
            {
                $violations = $context->violations();

                if ($violations !== null)
                {
                    return yield $context->delivery(
                        new AddDriverDocumentValidationFailed($command->driverId, $violations)
                    );
                }

                try
                {
                    yield $eventSourcingProvider->load(
                        id: $command->driverId,
                        context: $context,
                        onLoaded: static function (Driver $driver) use ($command, $documentStore): \Generator
                        {
                            $document   = self::createDocument($command);
                            $documentId = yield $documentStore->store($document);

                            $driver->attachDocument(
                                new DriverDocument(
                                    id: $documentId,
                                    type: DriverDocumentType::create($command->type),
                                    status: DriverDocumentStatus::moderation()
                                )
                            );
                        }
                    );
                }
                catch (AggregateNotFound)
                {
                    yield $context->delivery(
                        AddDriverDocumentValidationFailed::driverNotFound($command->driverId)
                    );
                }
            }
        );
    }

    private static function createDocument(AddDriverDocument $command): Document
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
