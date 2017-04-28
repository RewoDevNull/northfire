<?php

namespace Northfire\Domain\Model\Member\Command;

use Northfire\Domain\Model\Member\MemberId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangeNameCommand
 *
 * @package Northfire\Domain\Model\Member\Handler
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
final class ChangeName
{
    /** @var string */
    public $memberId;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    public $firstName;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    public $lastName;

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId(): MemberId
    {
        return MemberId::fromString($this->memberId);
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->lastName;
    }
}