<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument\Add\Contract;

use App\Driver\DriverId;
use ServiceBus\Common\Context\ValidationViolation;
use ServiceBus\Common\Context\ValidationViolations;

/**
 * Validation error when adding document
 *
 * @api
 * @see AddDriverDocument
 *
 * @psalm-immutable
 */
final class AddDriverDocumentValidationFailed
{
    /**
     * Driver aggregate id
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

    public static function incorrectImage(DriverId $driverId, string $message): self
    {
        return new self(
            $driverId,
            new ValidationViolations([
                    new ValidationViolation(
                        property: 'payload',
                        message: $message
                    )
                ]
            )
        );
    }

    public static function driverNotFound(DriverId $driverId): self
    {
        return new self(
            $driverId,
            new ValidationViolations([
                    new ValidationViolation(
                        property: 'driverId',
                        message: 'Driver with specified id not found'
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
