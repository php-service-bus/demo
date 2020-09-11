<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver;

use ServiceBus\EventSourcing\AggregateId;

/**
 * Driver id
 */
final class DriverId extends AggregateId
{
}
