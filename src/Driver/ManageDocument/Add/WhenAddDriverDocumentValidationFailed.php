<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument\Add;

use App\Driver\ManageDocument\Add\Contract\AddDriverDocumentValidationFailed;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 * Incorrect data to store a document
 */
final class WhenAddDriverDocumentValidationFailed
{
    #[EventListener]
    public function on(AddDriverDocumentValidationFailed $event, ServiceBusContext $context): void
    {
        $context->logger()->info(
            'Incorrect data to store a document',
            ['violations' => $event->violations->violations]
        );
    }
}
