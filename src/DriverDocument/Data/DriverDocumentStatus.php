<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument\Data;

use App\DriverDocument\Exceptions\IncorrectDriverDocumentStatus;

/**
 * Document status
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
     * @param string $status
     *
     * @return self
     *
     * @throws \App\DriverDocument\Exceptions\IncorrectDriverDocumentStatus
     */
    public static function create(string $status): self
    {
        if(false === isset(self::LIST[$status]))
        {
            throw new IncorrectDriverDocumentStatus($status);
        }

        return new self($status);
    }

    /**
     * @return self
     */
    public static function verified(): self
    {
        return new self(self::VERIFIED);
    }

    /**
     * @return self
     */
    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    /**
     * @return self
     */
    public static function moderation(): self
    {
        return new self(self::MODERATION);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param DriverDocumentStatus $documentStatus
     *
     * @return bool
     */
    public function equals(DriverDocumentStatus $documentStatus): bool
    {
        return $this->value === $documentStatus->value;
    }

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }
}
