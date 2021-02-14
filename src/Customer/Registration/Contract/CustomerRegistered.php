<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration\Contract;

use App\Customer\CustomerId;

/**
 * User successfully registered
 *
 * @api
 * @see RegisterCustomer
 *
 * @psalm-immutable
 */
final class CustomerRegistered
{
    /**
     * Request operation id
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $correlationId;

    /**
     * Customer identifier
     *
     * @psalm-readonly
     *
     * @var CustomerId
     */
    public $customerId;

    public function __construct(string $correlationId, CustomerId $customerId)
    {
        $this->correlationId = $correlationId;
        $this->customerId    = clone $customerId;
    }
}
