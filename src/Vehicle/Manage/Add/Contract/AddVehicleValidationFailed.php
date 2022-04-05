<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

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
     * @psalm-readonly
     *
     * @var VehicleId|null
     */
    public $vehicleId;

    /**
     * State registration number
     *
     * @psalm-readonly
     *
     * @var string
     */
    public $registrationNumber;

    public static function invalidBrand(string $registrationNumber): self
    {
        return new self(
            registrationNumber: $registrationNumber,
            violations: new ValidationViolations(
                [
                    new ValidationViolation(
                        property: 'brand',
                        message: 'Car brand not found'
                    )
                ]
            )
        );
    }

    public static function duplicateStateRegistrationNumber(string $registrationNumber, VehicleId $vehicleId): self
    {
        return new self(
            registrationNumber: $registrationNumber,
            violations: new ValidationViolations(
                [
                    new ValidationViolation(
                        property: 'registrationNumber',
                        message: 'The car with the specified registration number is already registered'
                    )
                ]
            ),
            vehicleId: clone $vehicleId
        );
    }

    public function __construct(
        string               $registrationNumber,
        ValidationViolations $violations,
        ?VehicleId           $vehicleId = null
    ) {
        $this->registrationNumber = $registrationNumber;
        $this->violations         = $violations;
        $this->vehicleId          = $vehicleId;
    }
}
