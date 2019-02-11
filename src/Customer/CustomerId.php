<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer;

use ServiceBus\EventSourcing\AggregateId;

/**
 * Customer identifier
 */
final class CustomerId extends AggregateId
{

}
