<?php

/**
 * PHP Service Bus (publish-subscribe pattern implementation) demo
 * Supports Saga pattern and Event Sourcing
 *
 * @author  Maksim Masiukevich <desperado@minsk-info.ru>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBusDemo\Customer\Command;

use Desperado\ServiceBus\Common\Contract\Messages\Command;
use ServiceBusDemo\Customer\Data\CustomerContacts;
use ServiceBusDemo\Customer\Data\CustomerFullName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
final class RegisterCustomer implements Command
{
    /**
     * @Assert\NotBlank(
     *     message="Operation identifier must be specified"
     * )
     *
     * @var string
     */
    private $operationId;

    /**
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
     * @Assert\NotBlank(
     *     message="Customer password must be specified"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 15,
     *      minMessage = "Customer password must be at least {{ limit }} characters long",
     *      maxMessage = "Customer password cannot be longer than {{ limit }} characters"
     * )
     *
     * @var string
     */
    private $clearPassword;

    /**
     * @Assert\NotBlank(
     *     message="customer email must be specified"
     * )
     * @Assert\Email(
     *     message="Email user is incorrect"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @param string $operationId
     * @param string $firstName
     * @param string $lastName
     * @param string $clearPassword
     * @param string $email
     *
     * @return self
     */
    public static function create(
        string $operationId,
        string $firstName,
        string $lastName,
        string $clearPassword,
        string $email
    ): self
    {
        $self = new self();

        $self->operationId   = $operationId;
        $self->firstName     = $firstName;
        $self->lastName      = $lastName;
        $self->clearPassword = $clearPassword;
        $self->email         = $email;

        return $self;
    }

    /**
     * @return CustomerFullName
     */
    public function fullName(): CustomerFullName
    {
        return new CustomerFullName(
            $this->firstName,
            $this->lastName
        );
    }

    /**
     * @return string
     */
    public function clearPassword(): string
    {
        return $this->clearPassword;
    }

    /**
     * @return CustomerContacts
     */
    public function contacts(): CustomerContacts
    {
        return new CustomerContacts(
            $this->email
        );
    }

    /**
     * @return string
     */
    public function operationId(): string
    {
        return $this->operationId;
    }

    private function __construct()
    {

    }
}
