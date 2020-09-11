<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument\Add\Contract;

use ServiceBus\Services\Contracts\ValidationFailedEvent;

/**
 * Validation error when adding document
 *
 * @api
 * @see AddDriverDocument
 *
 * @psalm-immutable
 */
final class AddDriverDocumentValidationFailed implements ValidationFailedEvent
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

    public static function incorrectImage(string $correlationId, string $message): ValidationFailedEvent
    {
        return new self($correlationId, ['payload' => [$message]]);
    }

    public static function driverNotFound(string $correlationId): ValidationFailedEvent
    {
        return new self($correlationId, ['driverId' => ['Driver with specified id not found']]);
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
