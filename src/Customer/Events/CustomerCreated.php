<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Events;

/**
 * Customer aggregate created
 *
 * internal event
 *
 * @property-read string $id
 * @property-read string $phone
 * @property-read string $email
 * @property-read string $firstName
 * @property-read string $lastName
 */
final class CustomerCreated
{
    /**
     * Customer aggregate id
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
     * @param string $id
     * @param string $phone
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     *
     * @return self
     */
    public static function create(string $id, string $phone, string $email, string $firstName, string $lastName): self
    {
        return new self($id, $phone, $email, $firstName, $lastName);
    }

    /**
     * @param string $id
     * @param string $phone
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     */
    private function __construct(string $id, string $phone, string $email, string $firstName, string $lastName)
    {
        $this->id        = $id;
        $this->phone     = $phone;
        $this->email     = $email;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }


}
