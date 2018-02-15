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

use Desperado\ServiceBus\Application\Bootstrap\AbstractBootstrap;
use Desperado\ServiceBus\Application\Bootstrap\BootstrapContainerConfiguration;
use Desperado\ServiceBus\Application\Bootstrap\BootstrapServicesDefinitions;
use Desperado\ServiceBusDemo\Application\DependencyInjection\DemoExtension;

/**
 *
 */
class Bootstrap extends AbstractBootstrap
{
    /**
     * @inheritdoc
     */
    protected function getBootstrapServicesDefinitions(): BootstrapServicesDefinitions
    {
        return BootstrapServicesDefinitions::create(
            'message_transport.rabbit_mq',
            'application_kernel',
            'sagas_storage',
            'application_scheduler_storage',
            'application_context'
        );
    }

    /**
     * @inheritdoc
     */
    protected function getBootstrapContainerConfiguration(): BootstrapContainerConfiguration
    {
        return BootstrapContainerConfiguration::create(
            [new DemoExtension()],
            [
                'transport_connection_dsn' => \getenv('TRANSPORT_CONNECTION_DSN'),
                'database_connection_dsn'  => \getenv('DATABASE_CONNECTION_DSN')
            ]
        );
    }
}
