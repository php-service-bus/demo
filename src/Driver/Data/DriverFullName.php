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
 * Driver full name data
 */
final class DriverFullName
{
    /**
     * First name
     *
     * @var string
     */
    private $first;

    /**
     * Last name
     *
     * @var string
     */
    private $last;

    /**
     * Patronymic
     *
     * @var string|null
     */
    private $patronymic;

    /**
     * @param string      $first
     * @param string      $last
     * @param string|null $patronymic
     */
    public function __construct(string $first, string $last, ?string $patronymic)
    {
        $this->first      = $first;
        $this->last       = $last;
        $this->patronymic = $patronymic;
    }

    /**
     * Receive first name
     *
     * @return string
     */
    public function first(): string
    {
        return $this->first;
    }

    /**
     * Receive last name
     *
     * @return string
     */
    public function last(): string
    {
        return $this->last;
    }

    /**
     * Receive patronymic
     *
     * @return string|null
     */
    public function patronymic(): ?string
    {
        return $this->patronymic;
    }
}
