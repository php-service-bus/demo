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
use App\Driver\Events\DriverAggregateCreated;
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
     * @noinspection PhpUnusedPrivateMethodInspection
     *
     * @param DriverAggregateCreated $event
     *
     * @return void
     */
    private function onDriverAggregateCreated(DriverAggregateCreated $event): void
    {
        $this->contacts = new DriverContacts($event->email, $event->phone);
        $this->fullName = new DriverFullName($event->firstName, $event->lastName, $event->patronymic);
    }
}
