<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument;

use App\Driver\ManageDocument\Exceptions\IncorrectDriverDocumentStatus;

/**
 *
 */
final class DriverDocumentStatus
{
    private const MODERATION = 'moderation';
    private const VERIFIED   = 'verified';
    private const REJECTED   = 'rejected';

    private const LIST       = [
        self::MODERATION => 'driver.document.status.moderation',
        self::VERIFIED   => 'driver.document.status.verified',
        self::REJECTED   => 'driver.document.status.rejected'
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @throws \App\Driver\ManageDocument\Exceptions\IncorrectDriverDocumentStatus
     */
    public static function create(string $status): self
    {
        if (isset(self::LIST[$status]) === false)
        {
            throw new IncorrectDriverDocumentStatus($status);
        }

        return new self($status);
    }

    public static function verified(): self
    {
        return new self(self::VERIFIED);
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    public static function moderation(): self
    {
        return new self(self::MODERATION);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(DriverDocumentStatus $documentStatus): bool
    {
        return $this->value === $documentStatus->value;
    }

    private function __construct(string $value)
    {
        $this->value = $value;
    }
}
