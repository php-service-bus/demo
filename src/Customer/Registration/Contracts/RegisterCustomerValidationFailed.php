<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration\Contracts;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * Invalid registration data
 *
 * @api
 * @see RegisterCustomer
 */
final class RegisterCustomerValidationFailed implements Event
{
    /**
     * Registration request Id
     *
     * @var string
     */
    public $correlationId;

    /**
     * List of validate violations
     *
     * [
     *    'propertyPath' => [
     *        0 => 'some message',
     *        ....
     *    ]
     * ]
     *
     * @var array<string, array<int, string>>
     */
    public $violations;

    /**
     * @param string                            $correlationId
     * @param array<string, array<int, string>> $violations
     *
     * @return self
     */
    public static function create(string $correlationId, array $violations): self
    {
        $self = new self();

        $self->correlationId = $correlationId;
        $self->violations    = $violations;

        return $self;
    }

    /**
     * @param string $correlationId
     *
     * @return self
     */
    public static function duplicatePhoneNumber(string $correlationId): self
    {
        return self::create($correlationId, ['phone' => ['Customer with the specified phone number is already registered']]);
    }

    private function __construct()
    {

    }
}
