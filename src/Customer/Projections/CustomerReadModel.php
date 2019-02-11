<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
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
