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
final class IncorrectDriverDocumentStatus extends \InvalidArgumentException
{
    /**
     * @param string $documentStatus
     */
    public function __construct(string $documentStatus)
    {
        parent::__construct(
            \sprintf('The specified document status ("%s") is incorrect', $documentStatus)
        );
    }
}
