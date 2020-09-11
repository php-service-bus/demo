<?php /** @noinspection PhpUnusedPrivateMethodInspection */

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer;

use App\Customer\Events\CustomerCreated;
use ServiceBus\EventSourcing\Aggregate;

/**
 * @method CustomerId id()
 */
final class Customer extends Aggregate
{
    /**
     * @var CustomerFullName
     */
    private $fullName;

    /**
     * @var CustomerContacts
     */
    private $contacts;

    /**
     * Create new customer aggregate
     */
    public static function register(CustomerFullName $fullName, CustomerContacts $contacts): self
    {
        $self = new self(CustomerId::new());

        $self->raise(
            new CustomerCreated($self->id(), $fullName, $contacts)
        );

        return $self;
    }

    private function onCustomerCreated(CustomerCreated $event): void
    {
        $this->fullName = $event->fullName;
        $this->contacts = $event->contacts;
    }
}
