<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Data;

/**
 * Driver contact details
 */
final class DriverContacts
{
    /**
     * Email address
     *
     * @var string
     */
    private $email;

    /**
     * Phone number
     *
     * @var string
     */
    private $phone;

    /**
     * @param string $email
     * @param string $phone
     */
    public function __construct(string $email, string $phone)
    {
        $this->email = $email;
        $this->phone = $phone;
    }

    /**
     * Receive email address
     *
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * Receive phone number
     *
     * @return string
     */
    public function phone(): string
    {
        return $this->phone;
    }
}
