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
use Desperado\ServiceBusDemo\Application\ApplicationContext;
use Desperado\ServiceBus\Services\ServiceInterface;
use Desperado\ServiceBusDemo\EmailNotifications\Command as EmailNotificationsCommands;
use Desperado\ServiceBusDemo\EmailNotifications\Event as EmailNotificationsEvents;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

/**
 * @Annotations\Service(
 *     loggerChannel="emailSent"
 * )
 */
class EmailNotificationsService implements ServiceInterface
{
    /**
     * @Annotations\CommandHandler()
     *
     * @param EmailNotificationsCommands\SendEmailCommand $command
     * @param ApplicationContext                          $context
     *
     * @return PromiseInterface
     */
    public function executeSendEmailCommand(
        EmailNotificationsCommands\SendEmailCommand $command,
        ApplicationContext $context
    ): PromiseInterface
    {
        /** We will not send a letter. Suppose that somewhere it was successfully sent */

        return new Promise(
            function() use ($command, $context)
            {
                $context->delivery(
                    EmailNotificationsEvents\EmailSentEvent::create(['requestId' => $command->getRequestId()])
                );
            }
        );
    }
}
