<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\CustomerRegistration\Contracts;

use ServiceBus\Common\Messages\Event;

/**
 * User successfully registered
 *
 * @api
 * @see RegisterCustomer
 *
 * @property-read string $correlationId
 * @property-read string $customerId
 */
final class CustomerRegistered implements Event
{
    /**
     * Request operation id
     *
     * @var string
     */
    public $correlationId;

    /**
     * Customer identifier
     *
     * @var string
     */
    public $customerId;

    /**
     * @param string $customerId
     * @param string $correlationId
     *
     * @return self
     */
    public static function create(string $customerId, string $correlationId): self
    {
        return new self($customerId, $correlationId);
    }

    /**
     * @param string $customerId
     * @param string $correlationId
     */
    private function __construct(string $customerId, string $correlationId)
    {
        $this->customerId    = $customerId;
        $this->correlationId = $correlationId;
    }
}
