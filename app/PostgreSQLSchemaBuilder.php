<?php

declare(strict_types = 1);

use function Amp\call;
use Amp\Promise;
use ServiceBus\Storage\Common\DatabaseAdapter;
use ServiceBus\Sagas\Module\SqlSchemaCreator as SagasSchemaCreator;
use ServiceBus\EventSourcingModule\SqlSchemaCreator as EventSourcingSqlSchemaCreator;

/**
 * Generate PostgreSQL schema for service-bus components
 */
final class PostgreSQLSchemaBuilder
{
    private const FIXTURES = [
        __DIR__ . '/schema/customer.sql'               => false,
        __DIR__ . '/schema/vehicle_brand.sql'          => false,
        __DIR__ . '/schema/vehicle_brand_fixtures.sql' => true,

    ];

    /**
     * @var DatabaseAdapter
     */
    private $adapter;

    /**
     * @param DatabaseAdapter $adapter
     */
    public function __construct(DatabaseAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Create all schemas
     *
     * @return Promise
     */
    public function build(): Promise
    {
        /** @psalm-suppress InvalidArgument */
        return call(
            function(array $fixtures): \Generator
            {
                yield $this->adapter->execute('CREATE EXTENSION IF NOT EXISTS "uuid-ossp";');

                yield (new SagasSchemaCreator($this->adapter, __DIR__ . '/../vendor/php-service-bus/sagas/'))->import();
                yield (new EventSourcingSqlSchemaCreator($this->adapter, __DIR__ . '/../vendor/php-service-bus/event-sourcing/'))->import();

                foreach($fixtures as $path => $multiple)
                {
                    yield $this->importFixture($path, $multiple);
                }
            },
            self::FIXTURES
        );
    }

    /**
     * @param string $fileName
     * @param bool   $multipleQuery
     *
     * @return Promise
     */
    public function importFixture(string $fileName, bool $multipleQuery = false): Promise
    {
        /** @psalm-suppress InvalidArgument */
        return call(
            function(string $fileName, bool $multipleQuery): \Generator
            {
                $content = \file_get_contents($fileName);

                $queries = true === $multipleQuery
                    ? \array_map('trim', \explode(\PHP_EOL, $content))
                    : [$content];

                foreach($queries as $query)
                {
                    if('' !== $query)
                    {
                        yield $this->adapter->execute($query);
                    }
                }
            },
            $fileName, $multipleQuery
        );
    }
}
