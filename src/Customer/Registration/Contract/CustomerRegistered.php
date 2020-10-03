<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
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
     * @var string
     */
    public $correlationId;

    /**
     * Customer identifier
     *
     * @var CustomerId
     */
    public $customerId;

    public function __construct(string $correlationId, CustomerId $customerId)
    {
        $this->correlationId = $correlationId;
        $this->customerId    = $customerId;
    }
}
