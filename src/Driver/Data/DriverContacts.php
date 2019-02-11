<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Data;

/**
 * Driver contact details
 *
 * @property-read string $email
 * @property-read string $phone
 */
final class DriverContacts
{
    /**
     * Email address
     *
     * @var string
     */
    public $email;

    /**
     * Phone number
     *
     * @var string
     */
    public $phone;

    /**
     * @param string $email
     * @param string $phone
     *
     * @return self
     */
    public static function create(string $email, string $phone): self
    {
        return new self($email, $phone);
    }

    /**
     * @param string $email
     * @param string $phone
     */
    private function __construct(string $email, string $phone)
    {
        $this->email = $email;
        $this->phone = $phone;
    }
}
