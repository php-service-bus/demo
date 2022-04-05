<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver\Registration\Contract;

use App\Driver\DriverId;
use ServiceBus\Common\Context\ValidationViolation;
use ServiceBus\Common\Context\ValidationViolations;

/**
 * Invalid registration data
 *
 * @api
 * @see RegisterDriver
 *
 * @psalm-immutable
 */
final class RegisterDriverValidationFailed
{
    /**
     * Driver id
     *
     * @psalm-readonly
     *
     * @var DriverId
     */
    public $driverId;

    /**
     * List of validate violations
     *
     * @var ValidationViolations
     */
    public $violations;

    public static function duplicatePhoneNumber(DriverId $driverId): self
    {
        return new self(
            $driverId,
            new ValidationViolations(
                [
                    new ValidationViolation(
                        property: 'phone',
                        message: 'Driver with the specified phone number is already registered'
                    )
                ]
            )
        );
    }

    public function __construct(DriverId $driverId, ValidationViolations $violations)
    {
        $this->driverId   = $driverId;
        $this->violations = $violations;
    }
}
