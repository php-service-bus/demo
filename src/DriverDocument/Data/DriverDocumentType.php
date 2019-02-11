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

use App\DriverDocument\Exceptions\IncorrectDriverDocumentType;

/**
 * Document type
 */
final class DriverDocumentType
{
    private const DRIVER_LICENSE_FRONT = 'driver_license_front';
    private const DRIVER_LICENSE_BACK  = 'driver_license_back';
    private const PASSPORT             = 'password';
    private const ADDITIONAL_DOCUMENT  = 'additional_document';

    private const LIST                 = [
        self::DRIVER_LICENSE_FRONT => 'driver.document.type.driver_license_front',
        self::DRIVER_LICENSE_BACK  => 'driver.document.type.driver_license_back',
        self::PASSPORT             => 'driver.document.type.password',
        self::ADDITIONAL_DOCUMENT  => 'driver.document.type.additional_document'
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $documentType
     *
     * @return self
     *
     * @throws \App\DriverDocument\Exceptions\IncorrectDriverDocumentType
     */
    public static function create(string $documentType): self
    {
        if(false === isset(self::LIST[$documentType]))
        {
            throw new IncorrectDriverDocumentType($documentType);
        }

        return new self($documentType);
    }

    /**
     * @return self
     */
    public static function passport(): self
    {
        return new self(self::PASSPORT);
    }

    /**
     * @return self
     */
    public static function additionalDocument(): self
    {
        return new self(self::ADDITIONAL_DOCUMENT);
    }

    /**
     * @return self
     */
    public static function driverLicenseFrontSide(): self
    {
        return new self(self::DRIVER_LICENSE_FRONT);
    }

    /**
     * @return self
     */
    public static function driverLicenseBackSide(): self
    {
        return new self(self::DRIVER_LICENSE_BACK);
    }

    /**
     * @param DriverDocumentType $documentType
     *
     * @return bool
     */
    public function equals(DriverDocumentType $documentType): bool
    {
        return $this->value === $documentType->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }
}
