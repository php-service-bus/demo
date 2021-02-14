<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration\Contract;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Register a new customer
 *
 * @api
 * @see CustomerRegistered
 * @see RegisterCustomerValidationFailed
 * @see CustomerRegistrationFailed
 *
 * @psalm-immutable
 */
final class RegisterCustomer
{
    /**
     * Phone number
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Phone number must be specified")
     *
     * @var string
     */
    public $phone;

    /**
     * Email address
     *
     * @psalm-readonly
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
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="First name must be specified")
     *
     * @var string
     */
    public $firstName;

    /**
     * Last name
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="First name must be specified")
     *
     * @var string
     */
    public $lastName;

    public function __construct(string $phone, string $email, string $firstName, string $lastName)
    {
        $this->phone     = $phone;
        $this->email     = $email;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }
}
