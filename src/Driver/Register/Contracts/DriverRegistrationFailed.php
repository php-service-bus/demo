<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Register\Contracts;

use Desperado\ServiceBus\Common\Contract\Messages\Event;

/**
 * Some error occured
 *
 * @api
 * @see RegisterDriver
 */
final class DriverRegistrationFailed implements Event
{
    /**
     * Registration request Id
     *
     * @var string
     */
    public $correlationId;

    /**
     * Error message
     *
     * @var string
     */
    public $reason;

    /**
     * @param string $correlationId
     * @param string $reason
     *
     * @return self
     */
    public static function create(string $correlationId, string $reason): self
    {
        $self                = new self();
        $self->correlationId = $correlationId;
        $self->reason        = $reason;

        return $self;
    }

    private function __construct()
    {

    }
}
