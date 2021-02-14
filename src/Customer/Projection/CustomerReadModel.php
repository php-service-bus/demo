<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Projection;

use ServiceBus\Storage\ActiveRecord\Table;

/**
 * Customer read model
 *
 * @property string $id
 * @property string $profile Json-encoded profile data
 */
final class CustomerReadModel extends Table
{
    protected static function tableName(): string
    {
        return 'customer';
    }
}
