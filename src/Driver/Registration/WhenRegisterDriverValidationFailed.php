<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration;

use App\Driver\Registration\Contract\RegisterDriverValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 * Incorrect operation parameters
 */
final class WhenRegisterDriverValidationFailed
{
    #[EventListener]
    public function on(RegisterDriverValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logger()->info(
            'Incorrect data to create a driver',
            ['violations' => $event->violations->violations]
        );
    }
}
