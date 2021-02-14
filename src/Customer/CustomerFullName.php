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
final class CustomerFullName
{
    /**
     * @psalm-readonly
     *
     * @var string
     */
    public $firstName;

    /**
     * @psalm-readonly
     *
     * @var string
     */
    public $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName  = $firstName;
        $this->lastName   = $lastName;
    }
}
