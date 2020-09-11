<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Registration\Contract;

use ServiceBus\Services\Contracts\ValidationFailedEvent;

/**
 * Invalid registration data
 *
 * @api
 * @see RegisterDriver
 *
 * @psalm-immutable
 */
final class RegisterDriverValidationFailed implements ValidationFailedEvent
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

    public static function create(string $correlationId, array $violations): ValidationFailedEvent
    {
        return new self($correlationId, $violations);
    }

    public static function duplicatePhoneNumber(string $correlationId): self
    {
        return new self($correlationId, ['phone' => ['Driver with the specified phone number is already registered']]);
    }

    /**
     * @psalm-param array<string, array<int, string>> $violations
     */
    public function __construct(string $correlationId, array $violations)
    {
        $this->correlationId = $correlationId;
        $this->violations    = $violations;
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
