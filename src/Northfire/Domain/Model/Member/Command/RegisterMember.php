<?php

namespace Northfire\Domain\Model\Member\Command;

use Northfire\Domain\Model\Member\MemberId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterMemberCommand
 *
 * @package Northfire\Domain\Model\Member\Handler
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
final class RegisterMember
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    public $firstName;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Type(type="datetime")
     */
    public $joiningDate;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    public $lastName;

    /** @var MemberId */
    public $memberId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    public $memberNumber;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    public $vehicleId;

    /**
     * @return string
     */
    public function firstName() : string
    {
        return $this->firstName;
    }

    /**
     * @return \DateTime
     */
    public function joiningDate() : \DateTime
    {
        return $this->joiningDate;
    }

    /**
     * @return string
     */
    public function lastName() : string
    {
        return $this->lastName;
    }

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId() : MemberId
    {
        if (null === $this->memberId) {
            $this->memberId = MemberId::generate();
        }

        return $this->memberId;
    }

    /**
     * @return string
     */
    public function memberNumber() : string
    {
        return $this->memberNumber;
    }

    /**
     * @return string
     */
    public function vehicleId() : string
    {
        return $this->vehicleId;
    }
}