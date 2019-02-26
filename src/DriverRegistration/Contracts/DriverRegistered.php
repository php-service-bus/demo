<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverRegistration\Contracts;

/**
 * New driver successful registered
 *
 * @api
 * @see RegisterDriver
 *
 * @property-read string $correlationId
 * @property-read string $driverId
 */
final class DriverRegistered
{
    /**
     * Request operation id
     *
     * @var string
     */
    public $correlationId;

    /**
     * Driver identifier
     *
     * @var string
     */
    public $driverId;

    /**
     * @param string $driverId
     * @param string $correlationId
     *
     * @return self
     */
    public static function create(string $driverId, string $correlationId): self
    {
        return new self($driverId, $correlationId);
    }

    /**
     * @param string $driverId
     * @param string $correlationId
     */
    private function __construct(string $driverId, string $correlationId)
    {
        $this->driverId      = $driverId;
        $this->correlationId = $correlationId;
    }
}
