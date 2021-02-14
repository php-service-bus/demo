<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Brand;

use Amp\Promise;
use ServiceBus\Storage\Sql\Finder\SqlFinder;
use function Amp\call;
use function ServiceBus\Storage\Sql\equalsCriteria;

/**
 *
 */
final class VehicleBrandFinder
{
    /**
     * @var SqlFinder
     */
    private $finder;

    public function __construct(SqlFinder $finder)
    {
        $this->finder = $finder;
    }

    public function findOneByTitle(string $title): Promise
    {
        return $this->find([equalsCriteria('title', $title)]);
    }

    public function findOneById(string $id): Promise
    {
        return $this->find([equalsCriteria('id', $id)]);
    }

    /**
     * @param \Latitude\QueryBuilder\CriteriaInterface[] $criteria
     *
     * @return Promise<\App\Vehicle\Brand\VehicleBrand|null>
     */
    private function find(array $criteria): Promise
    {
        return call(
            function () use ($criteria): \Generator
            {
                /**
                 * @psalm-var array{title: string, id: string}|null $result
                 */
                $result = yield $this->finder->findOneBy($criteria);

                if ($result !== null)
                {
                    return new VehicleBrand($result['id'], $result['title']);
                }

                return null;
            }
        );
    }
}
