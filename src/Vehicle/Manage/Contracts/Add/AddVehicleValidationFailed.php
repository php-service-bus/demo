<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Manage\Contracts\Add;

use ServiceBus\Services\Contracts\ValidationFailedEvent;

/**
 * Invalid vehicle details
 *
 * @api
 * @see AddVehicle
 *
 * @property-read string $correlationId
 * @property-read array  $violations
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
     * @var array<string, array<int, string>>
     */
    public $violations;

    /**
     * Vehicle identifier
     * Indicated in case the vehicle with registration number has already been added
     *
     * @var string|null
     */
    public $vehicleId;

    /**
     * @param string                            $correlationId
     * @param array<string, array<int, string>> $violations
     *
     * @return self
     */
    public static function create(string $correlationId, array $violations): ValidationFailedEvent
    {
        return new self($correlationId, $violations);
    }

    /**
     * @param string $correlationId
     *
     * @return self
     */
    public static function invalidBrand(string $correlationId): self
    {
        return new self($correlationId, ['brand' => ['Car brand not found']]);
    }

    /**
     * @param string $correlationId
     * @param string $vehicleId
     *
     * @return self
     */
    public static function duplicateStateRegistrationNumber(string $correlationId, string $vehicleId): self
    {
        $self = new self(
            $correlationId,
            ['registrationNumber' => ['The car with the specified registration number is already registered']]
        );

        $self->vehicleId = $vehicleId;

        return $self;
    }

    /**
     * @inheritDoc
     */
    public function correlationId(): string
    {
        return $this->correlationId;
    }

    /**
     * @inheritDoc
     */
    public function violations(): array
    {
        return $this->violations;
    }

    /**
     * @param string                            $correlationId
     * @param array<string, array<int, string>> $violations
     */
    private function __construct(string $correlationId, array $violations)
    {
        $this->correlationId = $correlationId;
        $this->violations    = $violations;
    }
}
