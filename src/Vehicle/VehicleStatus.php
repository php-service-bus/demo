<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Vehicle;

use App\Vehicle\Exceptions\IncorrectVehicleStatusSpecified;

/**
 * Vehicle status
 */
final class VehicleStatus
{
    private const AWAITING_MODERATION = 'moderation';
    private const CONFIRMED           = 'confirmed';
    private const BLOCKED             = 'blocked';

    private const CHOICES = [
        self::AWAITING_MODERATION => '{vehicle.status.moderation}',
        self::CONFIRMED           => '{vehicle.status.confirmed}',
        self::BLOCKED             => '{vehicle.status.blocked}'
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @throws \App\Vehicle\Exceptions\IncorrectVehicleStatusSpecified
     */
    public static function create(string $status): self
    {
        if (isset(self::CHOICES[$status]))
        {
            return new self($status);
        }

        throw new IncorrectVehicleStatusSpecified($status);
    }

    /**
     * Vehicle status: blocked
     *
     * @return self
     */
    public static function blocked(): self
    {
        return new self(self::BLOCKED);
    }

    /**
     * Vehicle status: confirmed
     */
    public static function confirmed(): self
    {
        return new self(self::CONFIRMED);
    }

    /**
     * Vehicle status: moderated
     */
    public static function moderation(): self
    {
        return new self(self::AWAITING_MODERATION);
    }

    /**
     * Receive a phrase for current status
     */
    public function phrase(): string
    {
        return self::CHOICES[$this->value];
    }

    public function equals(VehicleStatus $status): bool
    {
        return $this->value === $status->value;
    }

    public function active(): bool
    {
        return self::CONFIRMED === $this->value;
    }

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->value = $value;
    }
}
