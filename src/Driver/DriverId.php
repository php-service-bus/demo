<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver;

use ServiceBus\EventSourcing\AggregateId;

/**
 * Driver aggregate id
 */
final class DriverId extends AggregateId
{

}
