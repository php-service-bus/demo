<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer;

/**
 * @psalm-immutable
 */
final class CustomerContacts
{
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

    public function __construct(string $phone, string $email)
    {
        $this->phone = $phone;
        $this->email = $email;
    }
}
