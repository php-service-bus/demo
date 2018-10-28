<?php

/**
 * PHP Telegram Bot Api implementation
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */
declare(strict_types = 1);

namespace ServiceBusDemo\ManageCustomer\Contract\Rename;

use Desperado\ServiceBus\Common\Contract\Messages\Command;
use ServiceBusDemo\Customer\Data\CustomerFullName;

/**
 *
 */
final class RenameCustomer implements Command
{
    /**
     * Customer aggregate ID
     *
     * @Assert\NotBlank(
     *     message="Customer aggregate id must be specified"
     * )
     * @Assert\Uuid(
     *     message="Customer aggregate id must be a valid UUID"
     * )
     *
     * @var string
     */
    private $customerId;

    /**
     * New customer first name
     *
     * @Assert\NotBlank(
     *     message="Customer first name must be specified"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 30,
     *      minMessage = "Customer first name must be at least {{ limit }} characters long",
     *      maxMessage = "Customer first name cannot be longer than {{ limit }} characters"
     * )
     *
     * @var string
     */
    private $firstName;

    /**
     * New customer last name
     *
     * @Assert\NotBlank(
     *     message="Customer last name must be specified"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 30,
     *      minMessage = "Customer last name must be at least {{ limit }} characters long",
     *      maxMessage = "Customer last name cannot be longer than {{ limit }} characters"
     * )
     *
     * @var string
     */
    private $lastName;

    /**
     * @param string $customerId
     * @param string $firstName
     * @param string $lastName
     *
     * @return self
     */
    public static function create(string $customerId, string $firstName, string $lastName): self
    {
        $self = new self();

        $self->customerId = $customerId;
        $self->firstName = $firstName;
        $self->lastName = $lastName;

        return $self;
    }

    /**
     * Receive customer aggregate ID
     *
     * @return string
     */
    public function customerId(): string
    {
        return $this->customerId;
    }

    /**
     * Receive new full name data
     *
     * @return CustomerFullName
     */
    public function fullName(): CustomerFullName
    {
        return new CustomerFullName(
            $this->firstName,
            $this->lastName
        );
    }

    private function __construct()
    {

    }
}
