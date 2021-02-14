<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Customer\Registration\Contract;

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
     * Registration request Id
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $correlationId;

    /**
     * List of validate violations
     *
     * @psalm-readonly
     *
     * @var ValidationViolations
     */
    public $violations;

    public static function duplicatePhoneNumber(string $correlationId): self
    {
        return new self(
            $correlationId,
            new ValidationViolations([
                    new ValidationViolation(
                        property: 'phone',
                        message: 'Customer with the specified phone number is already registered'
                    )
                ]
            )
        );
    }

    public function __construct(string $correlationId, ValidationViolations $violations)
    {
        $this->correlationId = $correlationId;
        $this->violations    = $violations;
    }
}
