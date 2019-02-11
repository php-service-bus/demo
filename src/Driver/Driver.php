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
use App\Driver\Events\VehicleAdded;
use App\DriverDocument\Data\DriverDocument;
use App\DriverDocument\Data\DriverDocumentId;
use App\DriverDocument\Data\DriverDocuments;
use App\DriverDocument\Data\DriverDocumentType;
use App\Driver\Events\DocumentAdded;
use App\Driver\Events\DriverCreated;
use App\Vehicle\VehicleId;
use ServiceBus\EventSourcing\Aggregate;

/**
 * Driver aggregate
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
     * Vehicle collection
     *
     * @var array<string, \App\Vehicle\VehicleId>
     */
    private $vehicles = [];

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
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

        /** @noinspection PhpUnhandledExceptionInspection */
        $self->raise(
            DriverCreated::create((string) $self->id(), $phone, $email, $firstName, $lastName, $patronymic)
        );

        return $self;
    }

    /**
     * Attach new document
     *
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param string             $imagePath
     * @param DriverDocumentType $type
     *
     * @return void
     */
    public function attachDocument(string $imagePath, DriverDocumentType $type): void
    {
        /** @var DriverId $id */
        $id = $this->id();

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->raise(
            DocumentAdded::create($id, DriverDocumentId::new(), $type, $imagePath)
        );
    }

    /**
     * Add new vehicle
     *
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param VehicleId $vehicleId
     *
     * @return void
     */
    public function addVehicle(VehicleId $vehicleId): void
    {
        /** @var DriverId $id */
        $id = $this->id();

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->raise(
            VehicleAdded::create($id, $vehicleId)
        );
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param DriverCreated $event
     *
     * @return void
     */
    private function onDriverCreated(DriverCreated $event): void
    {
        $this->contacts  = DriverContacts::create($event->email, $event->phone);
        $this->fullName  = DriverFullName::create($event->firstName, $event->lastName, $event->patronymic);
        $this->documents = new DriverDocuments();
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param DocumentAdded $event
     *
     * @return void
     */
    private function onDocumentAddedToAggregate(DocumentAdded $event): void
    {
        $this->documents->add(
            DriverDocument::create($event->documentId, $event->imagePath, $event->type)
        );
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param VehicleAdded $event
     *
     * @return void
     */
    private function onVehicleAdded(VehicleAdded $event): void
    {
        $vehicleId = (string) $event->vehicleId;

        if(false === isset($this->vehicles[$vehicleId]))
        {
            $this->vehicles[$vehicleId] = $event->vehicleId;
        }
    }
}
