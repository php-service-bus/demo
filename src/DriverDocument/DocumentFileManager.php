<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\DriverDocument;

use function Amp\call;
use function Amp\File\
{exists, put, get, mkdir};
use Amp\Promise;
use App\DriverDocument\Data\DocumentImage;

/**
 * @todo: image encryption
 */
final class DocumentFileManager
{
    /**
     * Absolute path to image directory
     *
     * @var string
     */
    private $storageDirectory;

    /**
     * @param string $storageDirectory
     */
    public function __construct(string $storageDirectory)
    {
        $this->storageDirectory = \rtrim($storageDirectory, '/');
    }

    /**
     * Store new image
     *
     * @param DocumentImage $image
     *
     * @return Promise<string>
     */
    public function store(DocumentImage $image): Promise
    {
        $storageDirectory = $this->storageDirectory;

        /** @psalm-suppress InvalidArgument Incorrect psalm unpack parameters (...$args) */
        return call(
            static function(DocumentImage $image) use ($storageDirectory): \Generator
            {
                $directoryPath = self::createImageStoragePath($storageDirectory);

                /** @var bool $directoryExists */
                $directoryExists = yield exists($directoryPath);

                if(false === $directoryExists)
                {
                    yield mkdir($directoryPath, 0777, true);
                }

                $imagePath = $directoryPath . '/' . self::createImageFileName($image);

                yield put($imagePath, $image->payload);

                return $imagePath;
            },
            $image
        );
    }

    /**
     * Load exists image
     *
     * @param string $imagePath
     *
     * @return Promise<\App\DriverDocument\Data\DocumentImage|null>
     */
    public function load(string $imagePath): Promise
    {
        /** @psalm-suppress InvalidArgument Incorrect psalm unpack parameters (...$args) */
        return call(
            static function(string $imagePath): \Generator
            {
                /** @var bool $fileExists */
                $fileExists = yield exists($imagePath);

                if(true === $fileExists)
                {
                    /** @var string $fileContent */
                    $fileContent = yield get($imagePath);

                    return DocumentImage::fromString($fileContent);
                }
            },
            $imagePath
        );
    }

    /**
     * @param string $storageDirectory
     *
     * @return string
     */
    private static function createImageStoragePath(string $storageDirectory): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return \sprintf(
            '%s/%s/%s',
            $storageDirectory,
            \date('Y'),
            \date('m')
        );
    }

    /**
     * @noinspection PhpDocMissingThrowsInspection
     *
     * @param DocumentImage $image
     *
     * @return string
     */
    private static function createImageFileName(DocumentImage $image): string
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return \sprintf('%s.%s', \sha1(random_bytes(128)), $image->extension());
    }
}
