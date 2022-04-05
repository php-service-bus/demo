<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver;

use ServiceBus\EventSourcing\AggregateId;

/**
 * Driver id
 *
 * @psalm-immutable
 */
final class DriverId extends AggregateId
{
}
