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

use Desperado\ServiceBus\Services\Contracts\ValidationFailedEvent;

/**
 * Invalid vehicle details
 *
 * @api
 * @see AddVehicle
 */
final class AddVehicleValidationFailed implements ValidationFailedEvent
{
    /**
     * Request Id
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
     *
     * @return self
     */
    public static function duplicateStateRegistrationNumber(string $correlationId): self
    {
        return new self(
            $correlationId,
            ['registrationNumber' => ['The car with the specified registration number is already registered']]
        );
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
