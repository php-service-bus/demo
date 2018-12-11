<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver;

use App\Driver\Data\DriverContacts;
use App\Driver\Data\DriverFullName;
use App\Driver\Document\Data\DriverDocument;
use App\Driver\Document\Data\DriverDocumentId;
use App\Driver\Document\Data\DriverDocuments;
use App\Driver\Document\Data\DriverDocumentType;
use App\Driver\Events\DocumentAddedToAggregate;
use App\Driver\Events\DriverAggregateCreated;
use Desperado\ServiceBus\EventSourcing\Aggregate;

/**
 * Driver aggregate
 *
 * @method DriverId id
 */
final class Driver extends Aggregate
{
    /**
     * Contact information
     *
     * @var DriverContacts
     */
    private $contacts;

    /**
     * Driver full name data
     *
     * @var DriverFullName
     */
    private $fullName;

    /**
     * Uploaded documents collection
     *
     * @var DriverDocuments
     */
    private $documents;

    /**
     * @param string      $phone
     * @param string      $email
     * @param string      $firstName
     * @param string      $lastName
     * @param string|null $patronymic
     *
     * @return self
     */
    public static function register(string $phone, string $email, string $firstName, string $lastName, ?string $patronymic): self
    {
        $self = new self(DriverId::new());

        $self->raise(DriverAggregateCreated::create((string) $self->id(), $phone, $email, $firstName, $lastName, $patronymic));

        return $self;
    }

    /**
     * Attach new document
     *
     * @param string $imagePath
     * @param string $type
     *
     * @return void
     */
    public function attachDocument(string $imagePath, string $type): void
    {
        $this->raise(
            DocumentAddedToAggregate::create(
                $this->id(),
                DriverDocumentId::new(),
                DriverDocumentType::create($type),
                $imagePath
            )
        );
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param DriverAggregateCreated $event
     *
     * @return void
     */
    private function onDriverAggregateCreated(DriverAggregateCreated $event): void
    {
        $this->contacts  = new DriverContacts($event->email, $event->phone);
        $this->fullName  = new DriverFullName($event->firstName, $event->lastName, $event->patronymic);
        $this->documents = new DriverDocuments();
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param DocumentAddedToAggregate $event
     *
     * @return void
     */
    private function onDocumentAddedToAggregate(DocumentAddedToAggregate $event): void
    {
        $this->documents->add(new DriverDocument($event->documentId, $event->imagePath, $event->type));
    }
}
