<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration;

use App\Driver\Registration\Contract\RegisterDriverValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Incorrect operation parameters
 */
final class WhenRegisterDriverValidationFailed
{
    /**
     * @EventListener()
     */
    public function on(RegisterDriverValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logContextMessage('Incorrect data to create a driver', ['violations' => $event->violations]);
    }
}
