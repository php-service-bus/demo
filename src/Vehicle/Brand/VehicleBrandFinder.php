<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Brand;

use function Amp\call;
use Amp\Promise;
use function Desperado\ServiceBus\Infrastructure\Storage\fetchOne;
use function Desperado\ServiceBus\Infrastructure\Storage\SQL\equalsCriteria;
use function Desperado\ServiceBus\Infrastructure\Storage\SQL\selectQuery;
use Desperado\ServiceBus\Infrastructure\Storage\StorageAdapter;

/**
 *
 */
final class VehicleBrandFinder
{
    private const TABLE_NAME = 'vehicle_brand';

    /**
     * @var StorageAdapter
     */
    private $adapter;

    /**
     * @param StorageAdapter $adapter
     */
    public function __construct(StorageAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Find car by brand title
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

                /** @var \Desperado\ServiceBus\Infrastructure\Storage\ResultSet $resultSet */
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
     * @param string $key
     * @param string $value
     *
     * @return \Generator<\App\Vehicle\Brand\VehicleBrand|null>
     */
    private function findOneBy(string $key, string $value): \Generator
    {
        $selectQuery   = selectQuery(self::TABLE_NAME)->where(equalsCriteria($key, $value));
        $compiledQuery = $selectQuery->compile();

        /** @var \Desperado\ServiceBus\Infrastructure\Storage\ResultSet $resultSet */
        $resultSet = yield $this->adapter->execute($compiledQuery->sql(), $compiledQuery->params());

        /** @var array{id:string, title:string}|null $data */
        $data = yield fetchOne($resultSet);

        unset($resultSet);

        if(null !== $data)
        {
            return VehicleBrand::create($data['id'], $data['title']);
        }
    }
}