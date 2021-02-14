<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Add\Contract;

use App\Vehicle\VehicleId;
use ServiceBus\Common\Context\ValidationViolation;
use ServiceBus\Common\Context\ValidationViolations;

/**
 * Invalid vehicle details
 *
 * @api
 * @see AddVehicle
 *
 * @psalm-immutable
 */
final class AddVehicleValidationFailed
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

    /**
     * Vehicle identifier
     * Indicated in case the vehicle with registration number has already been added
     *
     * @var VehicleId|null
     */
    public $vehicleId;

    public static function invalidBrand(string $correlationId): self
    {
        return new self(
            $correlationId,
            new ValidationViolations([
                    new ValidationViolation(
                        property: 'brand',
                        message: 'Car brand not found'
                    )
                ]
            )
        );
    }

    public static function duplicateStateRegistrationNumber(string $correlationId, VehicleId $vehicleId): self
    {
        return new self(
            $correlationId,
            new ValidationViolations([
                    new ValidationViolation(
                        property: 'registrationNumber',
                        message: 'The car with the specified registration number is already registered'
                    )
                ]
            ),
            clone $vehicleId
        );
    }

    public function __construct(string $correlationId, ValidationViolations $violations, ?VehicleId $vehicleId = null)
    {
        $this->correlationId = $correlationId;
        $this->violations    = $violations;
        $this->vehicleId = $vehicleId;
    }

}
