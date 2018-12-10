<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Exceptions;

/**
 *
 */
final class IncorrectVehicleStatusSpecified extends \RuntimeException
{
    /**
     * @param string $status
     */
    public function __construct(string $status)
    {
        parent::__construct(
            \sprintf('Invalid vehicle status indicated ("%s")', $status)
        );
    }
}
