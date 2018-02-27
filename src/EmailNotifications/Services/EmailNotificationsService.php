<?php

/**
 * PHP Service Bus (CQS implementation)
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace Desperado\ServiceBusDemo\EmailNotifications\Services;

use Desperado\ServiceBus\Annotations;
use Desperado\ServiceBus\ServiceInterface;
use Desperado\ServiceBusDemo\Application\ApplicationContext;
use Desperado\ServiceBusDemo\EmailNotifications\Command as EmailNotificationsCommands;
use Desperado\ServiceBusDemo\EmailNotifications\Event as EmailNotificationsEvents;

/**
 * @Annotations\Services\Service(
 *     loggerChannel="emailSent"
 * )
 */
class EmailNotificationsService implements ServiceInterface
{
    /**
     * @Annotations\Services\CommandHandler()
     *
     * @param EmailNotificationsCommands\SendEmailCommand $command
     * @param ApplicationContext                          $context
     *
     * @return void
     */
    public function executeSendEmailCommand(
        EmailNotificationsCommands\SendEmailCommand $command,
        ApplicationContext $context
    ): void
    {
        /** We will not send a letter. Suppose that somewhere it was successfully sent */

        $context->delivery(
            EmailNotificationsEvents\EmailSentEvent::create(['requestId' => $command->getRequestId()])
        );
    }
}
