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

use App\Driver\ManageDocument\Add\Contract\AddDriverDocumentValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Annotations\EventListener;

/**
 * Incorrect data to store a document
 */
final class WhenAddDriverDocumentValidationFailed
{
    /**
     * @EventListener()
     */
    public function on(AddDriverDocumentValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logContextMessage('Incorrect data to store a document', ['violations' => $event->violations]);
    }
}
