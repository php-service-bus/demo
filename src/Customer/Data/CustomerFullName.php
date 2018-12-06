<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Data;

/**
 * Customer full name data
 */
final class CustomerFullName
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
     * @param string $first
     * @param string $last
     */
    public function __construct(string $first, string $last)
    {
        $this->first = $first;
        $this->last  = $last;
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
}
