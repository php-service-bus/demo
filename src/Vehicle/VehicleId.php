<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle;

use Desperado\ServiceBus\EventSourcing\AggregateId;

/**
 * Vehicle aggregate id
 */
final class VehicleId extends AggregateId
{

}
