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
 * Driver full name data
 *
 * @property-read string      $first
 * @property-read string      $last
 * @property-read string|null $patronymic
 */
final class DriverFullName
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
     * Patronymic
     *
     * @var string|null
     */
    public $patronymic;

    /**
     * @param string      $first
     * @param string      $last
     * @param string|null $patronymic
     *
     * @return self
     */
    public static function create(string $first, string $last, ?string $patronymic): self
    {
        return new self($first, $last, $patronymic);
    }

    /**
     * @param string      $first
     * @param string      $last
     * @param string|null $patronymic
     */
    private function __construct(string $first, string $last, ?string $patronymic)
    {
        $this->first      = $first;
        $this->last       = $last;
        $this->patronymic = $patronymic;
    }
}
