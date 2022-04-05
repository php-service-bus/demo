<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver;

/**
 * @psalm-immutable
 */
final class DriverFullName
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

    /**
     * @psalm-readonly
     *
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
