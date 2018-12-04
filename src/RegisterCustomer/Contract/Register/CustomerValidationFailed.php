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
final class CustomerValidationFailed implements Event
{
    /**
     * Violations list
     *
     * @psalm-var array<string, array<int, string>>
     *
     * @var array
     */
    private $violations;

    /**
     * @param array<string, array<int, string>> $violations
     *
     * @return self
     */
    public static function create(array $violations): self
    {
        $self = new self();

        $self->violations  = $violations;

        return $self;
    }

    /**
     * Receive violations
     *
     * @return array<string, array<int, string>>
     */
    public function violations(): array
    {
        return $this->violations;
    }

    private function __construct()
    {

    }
}
