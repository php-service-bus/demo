<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Driver\ManageDocument\Exceptions;

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
