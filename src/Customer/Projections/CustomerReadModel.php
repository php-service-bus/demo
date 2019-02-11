<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Projections;

use ServiceBus\Storage\ActiveRecord\Table;

/**
 *
 */
final class CustomerReadModel extends Table
{
    /**
     * @inheritDoc
     */
    protected static function tableName(): string
    {
        return 'customer';
    }
}
