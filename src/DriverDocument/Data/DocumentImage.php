<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument\Data;

use App\DriverDocument\Exceptions\IncorrectMessageData;

/**
 * Image entry
 *
 * @property-read int $width
 * @property-read int $height
 * @property-read string $mimeType
 * @property-read string $payload
 */
final class DocumentImage
{
    private const ACCEPTED_MIME_TYPES = [
        'image/gif'     => 'gif',
        'image/jpeg'    => 'jpeg',
        'image/pjpeg'   => 'jpeg',
        'image/png'     => 'png',
        'image/svg+xml' => 'svg'
    ];

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * FIle mime type
     *
     * @var string
     */
    public $mimeType;

    /**
     * Image content
     *
     * @var string
     */
    public $payload;

    /**
     * Create entry from base64-encoded string
     *
     * @param string $imageContent
     *
     * @return self
     *
     * @throws \App\DriverDocument\Exceptions\IncorrectMessageData
     */
    public static function fromString(string $imageContent): self
    {
        if('' === $imageContent)
        {
            throw new IncorrectMessageData('Empty image content');
        }

        $imageData = \getimagesizefromstring($imageContent);

        if(false !== $imageData)
        {
            $width    = (int) ($imageData[0] ?? 0);
            $height   = (int) ($imageData[1] ?? 0);
            $mimeType = (string) ($imageData['mime'] ?? '');

            if(0 !== $width && 0 !== $height && true === isset(self::ACCEPTED_MIME_TYPES[$mimeType]))
            {
                return self::create($width, $height, $mimeType, $imageContent);
            }
        }

        throw new IncorrectMessageData('Failed to decode image');
    }

    /**
     * @param int    $width
     * @param int    $height
     * @param string $mimeType
     * @param string $payload
     *
     * @return self
     */
    public static function create(int $width, int $height, string $mimeType, string $payload): self
    {
        return new self($width, $height, $mimeType, $payload);
    }

    /**
     * Receive related image extension
     *
     * @return string
     */
    public function extension(): string
    {
        return self::ACCEPTED_MIME_TYPES[$this->mimeType];
    }

    /**
     * @param int    $width
     * @param int    $height
     * @param string $mimeType
     * @param string $payload
     */
    private function __construct(int $width, int $height, string $mimeType, string $payload)
    {
        $this->width    = $width;
        $this->height   = $height;
        $this->mimeType = $mimeType;
        $this->payload  = $payload;
    }
}
