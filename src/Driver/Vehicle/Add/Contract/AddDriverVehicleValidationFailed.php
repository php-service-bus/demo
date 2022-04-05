<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver\Vehicle\Add\Contract;

use App\Driver\DriverId;
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
     * List of validate violations
     *
     * @psalm-readonly
     *
     * @var ValidationViolations
     */
    public $violations;

    /**
     * Driver aggregate id
     *
     * @psalm-readonly
     *
     * @var DriverId
     */
    public $driverId;

    public static function driverNotFound(DriverId $driverId): self
    {
        return new self(
            $driverId,
            new ValidationViolations(
                [
                    new ValidationViolation(
                        property: 'driverId',
                        message: 'Specified driver not found'
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
