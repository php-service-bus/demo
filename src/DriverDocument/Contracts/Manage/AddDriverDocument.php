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

use ServiceBus\Common\Messages\Command;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Add new driver document
 *
 * @api
 * @see DriverDocumentAdded
 * @see AddDriverDocumentFailure
 * @see AddDriverDocumentValidationFailed
 *
 * @property-read string $type
 * @property-read string $payload
 * @property-read string $driverId
 */
final class AddDriverDocument implements Command
{
    /**
     * Document type
     *
     * @Assert\NotBlank(message="Document type must be specified")
     * @Assert\Choice(
     *     choices={
     *         "driver_license_front",
     *         "driver_license_back",
     *         "password",
     *         "additional_document"
     *     },
     *     message="Choose a valid document type"
     * )
     *
     * @see DriverDocumentType
     *
     * @var string
     */
    public $type;

    /**
     * Bas64-encoded image
     *
     * @Assert\NotBlank(message="Bas64-encoded image must be specified")
     *
     * @var string
     */
    public $payload;

    /**
     * Driver identifier
     *
     * @Assert\NotBlank(message="Driver id must be specified")
     *
     * @var string
     */
    public $driverId;

    /**
     * @param string $type
     * @param string $payload
     * @param string $driverId
     *
     * @return self
     */
    public static function create(string $type, string $payload, string $driverId): self
    {
        return new self($type, $payload, $driverId);
    }

    /**
     * @param string $type
     * @param string $payload
     * @param string $driverId
     */
    private function __construct(string $type, string $payload, string $driverId)
    {
        $this->type     = $type;
        $this->payload  = $payload;
        $this->driverId = $driverId;
    }
}
