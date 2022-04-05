<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace App\Driver\ManageDocument\Add\Contract;

use App\Driver\DriverId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Add new driver document
 *
 * @api
 * @see DriverDocumentAdded
 * @see AddDriverDocumentFailure
 * @see AddDriverDocumentValidationFailed
 *
 * @psalm-immutable
 */
final class AddDriverDocument
{
    /**
     * Document type
     *
     * @psalm-readonly
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
     * Document file name (with extension)
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Document file name must be specified")
     *
     * @var string
     */
    public $filename;

    /**
     * Document media type
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Document media type must be specified")
     *
     * @var string
     */
    public $mimeType;

    /**
     * Bas64-encoded image
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Bas64-encoded image must be specified")
     *
     * @var string
     */
    public $payload;

    /**
     * Driver identifier
     *
     * @psalm-readonly
     *
     * @Assert\NotBlank(message="Driver id must be specified")
     *
     * @var DriverId
     */
    public $driverId;

    public function __construct(string $type, string $filename, string $mimeType, string $payload, DriverId $driverId)
    {
        $this->type     = $type;
        $this->filename = $filename;
        $this->mimeType = $mimeType;
        $this->payload  = $payload;
        $this->driverId = $driverId;
    }
}
