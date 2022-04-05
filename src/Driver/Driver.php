<?php /** @noinspection PhpUnusedPrivateMethodInspection */

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver;

use App\Driver\Events\DocumentAdded;
use App\Driver\Events\DriverCreated;
use App\Driver\Events\VehicleAdded;
use App\Driver\ManageDocument\DriverDocument;
use App\Driver\ManageDocument\DriverDocumentStatus;
use App\Vehicle\VehicleId;
use ServiceBus\EventSourcing\Aggregate;

/**
 * Driver aggregate
 *
 * @method DriverId id()
 */
final class Driver extends Aggregate
{
    /**
     * Driver name
     *
     * @var DriverFullName
     */
    private $fullName;

    /**
     * Driver contacts
     *
     * @var DriverContacts
     */
    private $contacts;

    /**
     * Attached documents
     *
     * @var DriverDocument[]
     */
    private $documents = [];

    /**
     * Vehicle collection
     *
     * @var VehicleId[]
     */
    private $vehicles = [];

    /**
     * Create a new driver
     */
    public static function register(DriverFullName $fullName, DriverContacts $contacts): self
    {
        $self = new self(DriverId::new());

        $self->raise(
            new DriverCreated($self->id(), $fullName, $contacts)
        );

        return $self;
    }

    /**
     * Attach new document
     *
     * @throws \InvalidArgumentException
     */
    public function attachDocument(DriverDocument $document): void
    {
        if (isset($this->documents[$document->id->toString()]) === false)
        {
            $this->raise(
                new DocumentAdded($this->id(), $document->id, $document->type)
            );

            return;
        }

        throw new \InvalidArgumentException(\sprintf('File `%s` already attached', $document->id->toString()));
    }

    /**
     * Add new vehicle
     */
    public function addVehicle(VehicleId $vehicleId): void
    {
        $this->raise(
            new VehicleAdded($this->id(), $vehicleId)
        );
    }

    private function onVehicleAdded(VehicleAdded $event): void
    {
        $this->vehicles[] = $event->vehicleId;
    }

    private function onDocumentAdded(DocumentAdded $event): void
    {
        $this->documents[$event->documentId->toString()] = new DriverDocument(
            $event->documentId,
            $event->type,
            DriverDocumentStatus::moderation()
        );
    }

    private function onDriverCreated(DriverCreated $event): void
    {
        $this->fullName = $event->fullName;
        $this->contacts = $event->contacts;
    }
}
