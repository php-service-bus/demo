<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Events;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * Vehicle was added to driver profile
 *
 * internal event
 */
final class VehicleAddedToDriver implements Event
{

}
