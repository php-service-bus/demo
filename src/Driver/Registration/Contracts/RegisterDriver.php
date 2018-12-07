<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration\Contracts;

use Desperado\ServiceBus\Common\Contract\Messages\Command;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Register a new driver
 *
 * @api
 * @see DriverRegistered
 * @see RegisterDriverValidationFailed
 * @see DriverRegistrationFailed
 */
final class RegisterDriver implements Command
{
    /**
     * Phone number
     *
     * @Assert\NotBlank(message="Phone number must be specified")
     *
     * @var string
     */
    public $phone;

    /**
     * Email address
     *
     * @Assert\NotBlank(message="Email address must be specified")
     * @Assert\Email(message="Incorrect email address")
     *
     * @var string
     */
    public $email;

    /**
     * First name
     *
     * @Assert\NotBlank(message="First name must be specified")
     *
     * @var string
     */
    public $firstName;

    /**
     * Last name
     *
     * @Assert\NotBlank(message="First name must be specified")
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
     * @param string      $phone
     * @param string      $email
     * @param string      $firstName
     * @param string      $lastName
     * @param string|null $patronymic
     *
     * @return self
     */
    public static function create(string $phone, string $email, string $firstName, string $lastName, ?string $patronymic): self
    {
        $self             = new self();
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
