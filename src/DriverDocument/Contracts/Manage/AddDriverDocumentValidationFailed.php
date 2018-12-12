<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument\Contracts\Manage;

use Desperado\ServiceBus\Services\Contracts\ValidationFailedEvent;

/**
 * Validation error when adding document
 *
 * @api
 * @see AddDriverDocument
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
