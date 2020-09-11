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
final class CustomerFullName
{
    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName  = $firstName;
        $this->lastName   = $lastName;
    }
}
