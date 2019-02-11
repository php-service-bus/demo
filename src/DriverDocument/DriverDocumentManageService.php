<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument;

use Amp\Promise;
use App\DriverDocument\Contracts\Manage\AddDriverDocument;
use App\DriverDocument\Contracts\Manage\AddDriverDocumentFailure;
use App\DriverDocument\Contracts\Manage\AddDriverDocumentValidationFailed;
use App\DriverDocument\Contracts\Manage\DriverDocumentAdded;
use App\DriverDocument\Data\DocumentImage;
use App\DriverDocument\Data\DriverDocumentType;
use App\DriverDocument\Exceptions\IncorrectMessageData;
use App\Driver\Driver;
use App\Driver\DriverId;
use App\Driver\Events\DocumentAdded;
use ServiceBus\Context\KernelContext;
use ServiceBus\EventSourcingModule\EventSourcingProvider;
use ServiceBus\Services\Annotations\EventListener;
use ServiceBus\Services\Annotations\CommandHandler;

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
     *     defaultValidationFailedEvent="App\DriverDocument\Contracts\Manage\AddDriverDocumentValidationFailed",
     *     defaultThrowableEvent="App\DriverDocument\Contracts\Manage\AddDriverDocumentFailure"
     * )
     *
     * @param AddDriverDocument     $command
     * @param KernelContext         $context
     * @param EventSourcingProvider $eventSourcingProvider
     * @param DocumentFileManager   $documentFileManager
     *
     * @return \Generator
     *
     * @throws \Throwable
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
                return yield $context->delivery(
                    AddDriverDocumentValidationFailed::driverNotFound($context->traceId())
                );
            }

            /** @var string $storedDocumentPath */
            $storedDocumentPath = yield $documentFileManager->store($imageEntry);

            $driver->attachDocument(
                $storedDocumentPath,
                DriverDocumentType::create($command->type)
            );

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
     * @param DocumentAdded $event
     * @param KernelContext $context
     *
     * @return Promise
     */
    public function whenDocumentAdded(DocumentAdded $event, KernelContext $context): Promise
    {
        return $context->delivery(
            DriverDocumentAdded::create($context->traceId(), (string) $event->driverId, (string) $event->documentId)
        );
    }

    /**
     * @EventListener()
     *
     * @param DriverDocumentAdded $event
     * @param KernelContext       $context
     *
     * @return void
     */
    public function whenDriverDocumentAdded(DriverDocumentAdded $event, KernelContext $context): void
    {
        $context->logContextMessage(
            'Document "{driverDocumentId}" successful added to driver "{driverId}"', [
                'driverDocumentId' => $event->documentId,
                'driverId'         => $event->driverId
            ]
        );
    }

    /**
     * @EventListener()
     *
     * @param AddDriverDocumentValidationFailed $event
     * @param KernelContext                     $context
     *
     * @return void
     */
    public function whenAddDriverDocumentValidationFailed(
        AddDriverDocumentValidationFailed $event,
        KernelContext $context
    ): void
    {
        $context->logContextMessage('Incorrect data to store a document', ['violations' => $event->violations]);
    }

    /**
     * @EventListener()
     *
     * @param AddDriverDocumentFailure $event
     * @param KernelContext            $context
     *
     * @return void
     */
    public function whenAddDriverDocumentFailure(AddDriverDocumentFailure $event, KernelContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the process of adding a document to the driver profile: %s', $event->reason)
            )
        );
    }
}
