<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
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
 * @psalm-suppress MutableDependency parent class already immutable, but not explicitly marked as immutable
 */
final class CustomerId extends AggregateId
{
}
