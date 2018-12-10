<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\Document\Exceptions;

/**
 *
 */
final class IncorrectDriverDocumentType extends \InvalidArgumentException
{
    /**
     * @param string $documentType
     */
    public function __construct(string $documentType)
    {
        parent::__construct(
            \sprintf('The specified document type ("%s") is incorrect', $documentType)
        );
    }
}
