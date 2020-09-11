<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver;

/**
 * @psalm-immutable
 */
final class DriverFullName
{
    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string|null
     */
    public $middleName;

    public function __construct(string $firstName, string $lastName, ?string $middleName)
    {
        $this->firstName  = $firstName;
        $this->lastName   = $lastName;
        $this->middleName = $middleName;
    }
}
