<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Document;

use Amp\Promise;
use App\Driver\Document\Contracts\Manage\AddDriverDocument;
use App\Driver\Document\Contracts\Manage\AddDriverDocumentValidationFailed;
use App\Driver\Document\Contracts\Manage\DriverDocumentAdded;
use App\Driver\Document\Data\DocumentImage;
use App\Driver\Document\Exceptions\IncorrectMessageData;
use App\Driver\Driver;
use App\Driver\DriverId;
use App\Driver\Events\DocumentAddedToAggregate;
use Desperado\ServiceBus\Application\KernelContext;
use Desperado\ServiceBus\EventSourcingProvider;
use Desperado\ServiceBus\Services\Annotations\CommandHandler;
use Desperado\ServiceBus\Services\Annotations\EventListener;

/**
 *
 */
final class DriverDocumentManageService
{
    /**
     * Add new driver document
     *
     * @todo: check active session
     *
     * @CommandHandler(
     *     validate=true,
     *     defaultValidationFailedEvent="App\Driver\Document\Contracts\Manage\AddDriverDocumentValidationFailed",
     *     defaultThrowableEvent="App\Driver\Document\Contracts\Manage\AddDriverDocumentFailure"
     * )
     *
     * @param AddDriverDocument     $command
     * @param KernelContext         $context
     * @param EventSourcingProvider $eventSourcingProvider
     * @param DocumentFileManager   $documentFileManager
     *
     * @return \Generator
     */
    public function storeDocument(
        AddDriverDocument $command,
        KernelContext $context,
        EventSourcingProvider $eventSourcingProvider,
        DocumentFileManager $documentFileManager
    ): \Generator
    {
        try
        {
            $imageEntry = DocumentImage::fromString((string) \base64_decode($command->payload));

            /** @var Driver|null $driver */
            $driver = yield $eventSourcingProvider->load(new DriverId($command->driverId));

            if(null === $driver)
            {
                return yield $context->delivery(AddDriverDocumentValidationFailed::driverNotFound($context->traceId()));
            }

            /** @var string $storedDocumentPath */
            $storedDocumentPath = yield $documentFileManager->store($imageEntry);

            $driver->attachDocument($storedDocumentPath, $command->type);

            return yield $eventSourcingProvider->save($driver, $context);
        }
        catch(IncorrectMessageData $exception)
        {
            return yield $context->delivery(
                AddDriverDocumentValidationFailed::incorrectImage($context->traceId(), $exception->getMessage())
            );
        }
    }

    /**
     * @EventListener()
     *
     * @param DocumentAddedToAggregate $event
     * @param KernelContext            $context
     *
     * @return Promise
     */
    public function whenDocumentAddedToAggregate(DocumentAddedToAggregate $event, KernelContext $context): Promise
    {
        return $context->delivery(
            DriverDocumentAdded::create($context->traceId(), (string) $event->driverId, (string) $event->documentId)
        );
    }
}
