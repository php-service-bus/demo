<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) demo
 * Supports Saga pattern and Event Sourcing
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBusDemo\App\Storage;

use function Amp\call;
use Amp\Promise;
use Desperado\ServiceBus\Infrastructure\Storage\StorageAdapter;

/**
 *
 */
final class StorageSchemaBuilder
{
    /**
     * @var StorageAdapter
     */
    private $applicationAdapter;

    /**
     * @param StorageAdapter $applicationAdapter
     */
    public function __construct(StorageAdapter $applicationAdapter)
    {
        $this->applicationAdapter = $applicationAdapter;
    }

    /**
     * @return Promise<null>
     */
    public function buildSchema(): Promise
    {
        $srcDirectory = __DIR__ . '/../../vendor/mmasiukevich/service-bus/src';

        $fixtures = [
            $srcDirectory . '/Sagas/SagaStore/Sql/schema/extensions.sql'                               => false,
            $srcDirectory . '/Sagas/SagaStore/Sql/schema/sagas_store.sql'                              => false,
            $srcDirectory . '/Sagas/SagaStore/Sql/schema/indexes.sql'                                  => true,
            $srcDirectory . '/Scheduler/Store/Sql/schema/scheduler_registry.sql'                       => false,
            $srcDirectory . '/Index/Storage/Sql/schema/event_sourcing_indexes.sql'                     => false,
            $srcDirectory . '/EventSourcing/EventStreamStore/Sql/schema/event_store_stream.sql'        => false,
            $srcDirectory . '/EventSourcing/EventStreamStore/Sql/schema/event_store_stream_events.sql' => false,
            $srcDirectory . '/EventSourcing/EventStreamStore/Sql/schema/event_store_snapshots.sql'     => false,
            $srcDirectory . '/EventSourcing/EventStreamStore/Sql/schema/indexes.sql'                   => true
        ];

        /** @psalm-suppress InvalidArgument Incorrect psalm unpack parameters (...$args) */
        return call(
            function(array $fixtures): \Generator
            {
                foreach($fixtures as $path => $multipleQueries)
                {
                    yield self::import($path, $multipleQueries);
                }
            },
            $fixtures
        );
    }

    /**
     * @param string $fileName
     * @param bool   $multipleQuery
     *
     * @return Promise<null>
     *
     * @throws \Throwable
     */
    private function import(string $fileName, bool $multipleQuery = false): Promise
    {
        $adapter = $this->applicationAdapter;

        /** @psalm-suppress InvalidArgument Incorrect psalm unpack parameters (...$args) */
        return call(
            static function(string $fileName, bool $multipleQuery) use ($adapter): \Generator
            {
                $content = \file_get_contents($fileName);

                $queries = true === $multipleQuery
                    ? \array_map('trim', \explode(\PHP_EOL, $content))
                    : [$content];

                foreach($queries as $query)
                {
                    if('' !== $query)
                    {
                        yield $adapter->execute($query);
                    }
                }
            },
            $fileName,
            $multipleQuery
        );
    }
}
