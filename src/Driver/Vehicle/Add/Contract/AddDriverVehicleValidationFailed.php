<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Vehicle\Add\Contract;

use ServiceBus\Common\Context\ValidationViolation;
use ServiceBus\Common\Context\ValidationViolations;

/**
 * Validation failed
 *
 * @api
 * @see AddDriverVehicle
 *
 * @psalm-immutable
 */
final class AddDriverVehicleValidationFailed
{
    /**
     * Request operation id
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

    public static function driverNotFound(string $correlationId): self
    {
        return new self(
            $correlationId,
            new ValidationViolations([
                    new ValidationViolation(
                        property: 'driverId',
                        message: 'Specified driver not found'
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
