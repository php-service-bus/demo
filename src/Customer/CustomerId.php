<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer;

use ServiceBus\EventSourcing\AggregateId;

/**
 * Customer identifier
 *
 * @psalm-immutable
 */
final class CustomerId extends AggregateId
{
}
