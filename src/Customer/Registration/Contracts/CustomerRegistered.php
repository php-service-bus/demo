<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration\Contracts;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * User successfully registered
 *
 * @api
 * @see RegisterCustomer
 */
final class CustomerRegistered implements Event
{
    /**
     * Customer identifier
     *
     * @var string
     */
    public $customerId;

    /**
     * Registration request Id
     *
     * @var string
     */
    public $correlationId;

    /**
     * @param string $customerId
     * @param string $correlationId
     *
     * @return self
     */
    public static function create(string $customerId, string $correlationId): self
    {
        $self = new self();

        $self->customerId    = $customerId;
        $self->correlationId = $correlationId;

        return $self;
    }

    private function __construct()
    {

    }
}
