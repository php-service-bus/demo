<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
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
     * @psalm-readonly
     *
     * @var string
     */
    public $phone;

    /**
     * Email address
     *
     * @psalm-readonly
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
