<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration\Contract;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Register a new driver
 *
 * @api
 * @see DriverRegistered
 * @see RegisterDriverValidationFailed
 * @see DriverRegistrationFailed
 *
 * @psalm-immutable
 */
final class RegisterDriver
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

    public function __construct(string $phone, string $email, string $firstName, string $lastName, ?string $patronymic)
    {
        $this->phone      = $phone;
        $this->email      = $email;
        $this->firstName  = $firstName;
        $this->lastName   = $lastName;
        $this->patronymic = $patronymic;
    }
}
