<?php

/**
 * PHP Service Bus (CQS implementation)
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace Desperado\ServiceBusDemo\Application;

use Desperado\ServiceBus\Application\AbstractKernel;
use Desperado\ServiceBusDemo\Customer;
use Desperado\ServiceBusDemo\EmailNotifications;

/**
 * Application kernel
 */
class Kernel extends AbstractKernel
{

    /**
     * @inheritdoc
     */
    protected function getSagasList(): array
    {
        return [
            Customer\CustomerVerificationSaga::class
        ];
    }
}
