<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle;

use ServiceBus\EventSourcing\AggregateId;

/**
 * Vehicle aggregate id
 *
 * @psalm-immutable
 */
final class VehicleId extends AggregateId
{
}
