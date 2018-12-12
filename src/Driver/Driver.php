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
use App\Driver\Events\VehicleAddedToAggregate;
use App\DriverDocument\Data\DriverDocument;
use App\DriverDocument\Data\DriverDocumentId;
use App\DriverDocument\Data\DriverDocuments;
use App\DriverDocument\Data\DriverDocumentType;
use App\Driver\Events\DocumentAddedToAggregate;
use App\Driver\Events\DriverAggregateCreated;
use App\Vehicle\VehicleId;
use Desperado\ServiceBus\EventSourcing\Aggregate;

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

        $self->raise(
            DriverAggregateCreated::create((string) $self->id(), $phone, $email, $firstName, $lastName, $patronymic)
        );

        return $self;
    }

    /**
     * Attach new document
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

        $this->raise(
            DocumentAddedToAggregate::create($id, DriverDocumentId::new(), $type, $imagePath)
        );
    }

    /**
     * Add new vehicle
     *
     * @param VehicleId $vehicleId
     *
     * @return void
     */
    public function addVehicle(VehicleId $vehicleId): void
    {
        /** @var DriverId $id */
        $id = $this->id();

        $this->raise(
            VehicleAddedToAggregate::create($id, $vehicleId)
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
        $this->documents->add(
            new DriverDocument($event->documentId, $event->imagePath, $event->type)
        );
    }

    /**
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param VehicleAddedToAggregate $event
     *
     * @return void
     */
    private function onVehicleAddedToAggregate(VehicleAddedToAggregate $event): void
    {
        $vehicleId = (string) $event->vehicleId;

        if(false === isset($this->vehicles[$vehicleId]))
        {
            $this->vehicles[$vehicleId] = $event->vehicleId;
        }
    }
}
