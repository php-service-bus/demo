<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\CustomerRegistration\Contracts;

use ServiceBus\Common\Messages\Command;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Register a new customer
 *
 * @api
 * @see CustomerRegistered
 * @see RegisterCustomerValidationFailed
 * @see CustomerRegistrationFailed
 *
 * @property-read string $phone
 * @property-read string $email
 * @property-read string $firstName
 * @property-read string $lastName
 */
final class RegisterCustomer implements Command
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
     * @param string $phone
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     *
     * @return self
     */
    public static function create(string $phone, string $email, string $firstName, string $lastName): self
    {
        return new self($phone, $email, $firstName, $lastName);
    }

    /**
     * @param string $phone
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     */
    private function __construct(string $phone, string $email, string $firstName, string $lastName)
    {
        $this->phone     = $phone;
        $this->email     = $email;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }
}
