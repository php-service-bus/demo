<?php

/**
 * Demo application, remotely similar to Uber
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace App\Vehicle\Brand;

/**
 * Vehicle brand
 *
 * @property-read string $id
 * @property-read string $title
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

    /**
     * @param string $id
     * @param string $title
     *
     * @return self
     */
    public static function create(string $id, string $title): self
    {
        return new self($id, $title);
    }

    /**
     * @param string $id
     * @param string $title
     */
    private function __construct(string $id, string $title)
    {
        $this->id    = $id;
        $this->title = $title;
    }
}
