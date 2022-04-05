<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Customer\Registration\Contract;

use App\Customer\CustomerId;
use ServiceBus\Common\Context\ValidationViolation;
use ServiceBus\Common\Context\ValidationViolations;

/**
 * Invalid registration data
 *
 * @api
 * @see RegisterCustomer
 *
 * @psalm-immutable
 */
final class RegisterCustomerValidationFailed
{
    /**
     * Customer Id
     *
     * @psalm-readonly
     *
     * @var CustomerId
     */
    public $customerId;

    /**
     * List of validate violations
     *
     * @psalm-readonly
     *
     * @var ValidationViolations
     */
    public $violations;

    public static function duplicatePhoneNumber(CustomerId $customerId): self
    {
        return new self(
            $customerId,
            new ValidationViolations(
                [
                    new ValidationViolation(
                        property: 'phone',
                        message: 'Customer with the specified phone number is already registered'
                    )
                ]
            )
        );
    }

    public function __construct(CustomerId $customerId, ValidationViolations $violations)
    {
        $this->customerId = $customerId;
        $this->violations = $violations;
    }
}
