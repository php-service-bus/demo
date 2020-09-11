<?php

/**
 * PHP Service Bus demo application
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Brand;

/**
 * Vehicle brand
 *
 * @psalm-immutable
 */
final class VehicleBrand
{
    /**
     * Brand identifier
     *
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    public function __construct(string $id, string $title)
    {
        $this->id    = $id;
        $this->title = $title;
    }
}
