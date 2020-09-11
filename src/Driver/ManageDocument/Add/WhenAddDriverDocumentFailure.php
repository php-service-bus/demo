<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument\Add;

use App\Driver\ManageDocument\Add\Contract\AddDriverDocumentFailure;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Error in the process of adding a document to the driver profile
 */
final class WhenAddDriverDocumentFailure
{
    /**
     * @EventListener()
     */
    public function on(AddDriverDocumentFailure $event, ServiceBusContext $context): void
    {
        $context->logContextThrowable(
            new \RuntimeException(
                \sprintf('Error in the process of adding a document to the driver profile: %s', $event->reason)
            )
        );
    }
}
