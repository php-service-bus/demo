<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Add\Contract;

use App\Vehicle\VehicleId;
use ServiceBus\Services\Contracts\ValidationFailedEvent;

/**
 * Invalid vehicle details
 *
 * @api
 * @see AddVehicle
 *
 * @psalm-immutable
 */
final class AddVehicleValidationFailed implements ValidationFailedEvent
{
    /**
     * Request operation id
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
     * @psalm-var array<string, array<int, string>>
     *
     * @var array
     */
    public $violations;

    /**
     * Vehicle identifier
     * Indicated in case the vehicle with registration number has already been added
     *
     * @var VehicleId|null
     */
    public $vehicleId;

    public static function create(string $correlationId, array $violations): ValidationFailedEvent
    {
        return new self($correlationId, $violations);
    }

    public static function invalidBrand(string $correlationId): self
    {
        return new self($correlationId, ['brand' => ['Car brand not found']]);
    }

    public static function duplicateStateRegistrationNumber(string $correlationId, VehicleId $vehicleId): self
    {
        return new self(
            $correlationId,
            ['registrationNumber' => ['The car with the specified registration number is already registered']],
            clone $vehicleId
        );
    }

    /**
     * @psalm-param array<string, array<int, string>> $violations
     */
    public function __construct(string $correlationId, array $violations, ?VehicleId $vehicleId = null)
    {
        $this->correlationId = $correlationId;
        $this->violations    = $violations;
        /** @psalm-suppress ImpurePropertyAssignment */
        $this->vehicleId     = $vehicleId;
    }

    public function correlationId(): string
    {
        return $this->correlationId;
    }

    public function violations(): array
    {
        return $this->violations;
    }
}
