<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Data;

/**
 * Customer full name data
 *
 * @property-read string $first
 * @property-read string $last
 */
final class CustomerFullName
{
    /**
     * First name
     *
     * @var string
     */
    public $first;

    /**
     * Last name
     *
     * @var string
     */
    public $last;

    /**
     * @param string $first
     * @param string $last
     *
     * @return self
     */
    public static function create(string $first, string $last): self
    {
        return new self($first, $last);
    }

    /**
     * @param string $first
     * @param string $last
     */
    private function __construct(string $first, string $last)
    {
        $this->first = $first;
        $this->last  = $last;
    }
}
