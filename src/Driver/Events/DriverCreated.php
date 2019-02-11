<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Events;

use ServiceBus\Common\Messages\Event;

/**
 * Driver aggregate created
 *
 * internal event
 */
final class DriverCreated implements Event
{
    /**
     * Driver aggregate id
     *
     * @var string
     */
    public $id;

    /**
     * Phone number
     *
     * @var string
     */
    public $phone;

    /**
     * Email address
     *
     * @var string
     */
    public $email;

    /**
     * First name
     *
     * @var string
     */
    public $firstName;

    /**
     * Last name
     *
     * @var string
     */
    public $lastName;

    /**
     * Patronymic
     *
     * @var string|null
     */
    public $patronymic;

    /**
     * @param string      $id
     * @param string      $phone
     * @param string      $email
     * @param string      $firstName
     * @param string      $lastName
     * @param string|null $patronymic
     *
     * @return self
     */
    public static function create(string $id, string $phone, string $email, string $firstName, string $lastName, ?string $patronymic): self
    {
        $self = new self();

        $self->id         = $id;
        $self->phone      = $phone;
        $self->email      = $email;
        $self->firstName  = $firstName;
        $self->lastName   = $lastName;
        $self->patronymic = $patronymic;

        return $self;
    }

    private function __construct()
    {

    }
}
