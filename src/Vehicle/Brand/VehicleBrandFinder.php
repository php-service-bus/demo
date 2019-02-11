<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Brand;

use function Amp\call;
use Amp\Promise;
use ServiceBus\Storage\Common\DatabaseAdapter;
use function ServiceBus\Storage\Sql\equalsCriteria;
use function ServiceBus\Storage\Sql\fetchOne;
use function ServiceBus\Storage\Sql\selectQuery;

/**
 *
 */
final class VehicleBrandFinder
{
    private const TABLE_NAME = 'vehicle_brand';

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
     * Find car by brand title
     *
     * @psalm-suppress MixedTypeCoercion
     *
     * @param string $title
     *
     * @return Promise<\App\Vehicle\Brand\VehicleBrand|null>
     */
    public function findOneByTitle(string $title): Promise
    {
        /** @psalm-suppress InvalidArgument Incorrect psalm unpack parameters (...$args) */
        return call(
            function(string $title): \Generator
            {
                $sql = \sprintf('SELECT * FROM %s WHERE title ILIKE ?', self::TABLE_NAME);

                /** @var \ServiceBus\Storage\Common\ResultSet $resultSet */
                $resultSet = yield $this->adapter->execute($sql, [$title]);

                /** @var array{id:string, title:string}|null $data */
                $data = yield fetchOne($resultSet);

                unset($resultSet);

                if(null !== $data)
                {
                    return VehicleBrand::create($data['id'], $data['title']);
                }
            },
            $title
        );
    }

    /**
     * Find car by brand id
     *
     * @psalm-suppress MixedTypeCoercion
     *
     * @param string $id
     *
     * @return Promise<\App\Vehicle\Brand\VehicleBrand|null>
     */
    public function findOneById(string $id): Promise
    {
        /** @psalm-suppress InvalidArgument Incorrect psalm unpack parameters (...$args) */
        return call(
            function(string $id): \Generator
            {
                return yield from $this->findOneBy('id', $id);
            },
            $id
        );
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param string $key
     * @param string $value
     *
     * @return \Generator
     * @throws \ServiceBus\Storage\Common\Exceptions\ConnectionFailed
     * @throws \ServiceBus\Storage\Common\Exceptions\InvalidConfigurationOptions
     * @throws \ServiceBus\Storage\Common\Exceptions\ResultSetIterationFailed
     * @throws \ServiceBus\Storage\Common\Exceptions\StorageInteractingFailed
     */
    private function findOneBy(string $key, string $value): \Generator
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $selectQuery   = selectQuery(self::TABLE_NAME)->where(equalsCriteria($key, $value));
        $compiledQuery = $selectQuery->compile();

        /** @var \ServiceBus\Storage\Common\ResultSet $resultSet */
        $resultSet = /** @noinspection PhpUnhandledExceptionInspection */
            yield $this->adapter->execute($compiledQuery->sql(), $compiledQuery->params());

        /** @var array{id:string, title:string}|null $data */
        $data = /** @noinspection PhpUnhandledExceptionInspection */
            yield fetchOne($resultSet);

        unset($resultSet);

        if(null !== $data)
        {
            return VehicleBrand::create($data['id'], $data['title']);
        }
    }
}
