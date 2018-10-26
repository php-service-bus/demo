<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) demo
 * Supports Saga pattern and Event Sourcing
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBusDemo\Customer\Data;

/**
 *
 */
final class CustomerCredentials
{
    /**
     * Customer password
     *
     * @var string
     */
    private $passwordHash;

    /**
     * A password algorithm constant denoting the algorithm to use when hashing the password
     *
     * @see \PASSWORD_DEFAULT
     * @see \PASSWORD_BCRYPT
     * @see \PASSWORD_ARGON2I
     *
     * @var int
     */
    private $algorithm;

    /**
     * @param string $passwordHash
     * @param int    $algorithm
     */
    public function __construct(string $passwordHash, int $algorithm)
    {
        $this->passwordHash = $passwordHash;
        $this->algorithm    = $algorithm;
    }

    /**
     * @param string $password
     * @param int    $algorithm
     *
     * @return self
     */
    public static function encodeClearPassword(string $password, int $algorithm = \PASSWORD_DEFAULT): self
    {
        return new self(\password_hash($password, $algorithm), $algorithm);
    }
}
