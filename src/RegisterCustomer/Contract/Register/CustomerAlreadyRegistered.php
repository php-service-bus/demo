<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) demo
 * Supports Saga pattern and Event Sourcing
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace App\RegisterCustomer\Contract\Register;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * @see RegisterCustomer
 */
final class CustomerAlreadyRegistered implements Event
{

    /**
     * Email address
     *
     * @var string
     */
    private $email;

    /**
     * @param string $email
     *
     * @return self
     */
    public static function create(string $email): self
    {
        $self = new self();

        $self->email = $email;

        return $self;
    }

    /**
     * Receive email address
     *
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    private function __construct()
    {

    }
}
