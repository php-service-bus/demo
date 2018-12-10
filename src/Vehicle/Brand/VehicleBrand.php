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
 */
final class VehicleBrand
{
    /**
     * Brand identifier
     *
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @param string $id
     * @param string $title
     *
     * @return self
     */
    public static function create(string $id, string $title): self
    {
        $self = new self();

        $self->id    = $id;
        $self->title = $title;

        return $self;
    }

    /**
     * Receive brand id
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Receive brand title
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}
