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

use Amp\Promise;
use App\Driver\Events\DocumentAdded;
use App\Driver\ManageDocument\Add\Contract\DriverDocumentAdded;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Services\Attributes\EventListener;

/**
 * Document successful attached to driver
 */
final class WhenDocumentAdded
{
    #[EventListener]
    public function on(DocumentAdded $event, ServiceBusContext $context): Promise
    {
        $context->logger()->info(
            'Document "{driverDocumentId}" successful added to driver "{driverId}"',
            [
                'driverDocumentId' => $event->documentId->toString(),
                'driverId'         => $event->driverId->toString()
            ]
        );

        return $context->delivery(
            new DriverDocumentAdded($context->metadata()->traceId(), $event->driverId, $event->documentId)
        );
    }
}
