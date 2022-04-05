<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver\Vehicle\Add;

use ServiceBus\Sagas\SagaId;

/**
 * @psalm-immutable
 */
final class AddDriverVehicleSagaId extends SagaId
{
}
