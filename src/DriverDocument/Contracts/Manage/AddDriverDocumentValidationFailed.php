<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument\Contracts\Manage;

use ServiceBus\Services\Contracts\ValidationFailedEvent;

/**
 * Validation error when adding document
 *
 * @api
 * @see AddDriverDocument
 *
 * @property-read string $correlationId
 * @property-read array  $violations
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
     * @var array<string, array<int, string>>
     */
    public $violations;

    /**
     * @inheritDoc
     */
    public static function create(string $correlationId, array $violations): ValidationFailedEvent
    {
        return new self($correlationId, $violations);
    }

    /**
     * @param string $correlationId
     * @param string $message
     *
     * @return self
     */
    public static function incorrectImage(string $correlationId, string $message): ValidationFailedEvent
    {
        return new self($correlationId, ['payload' => [$message]]);
    }

    /**
     * @param string $correlationId
     *
     * @return self
     */
    public static function driverNotFound(string $correlationId): ValidationFailedEvent
    {
        return new self($correlationId, ['driverId' => ['Driver with specified id not found']]);
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
