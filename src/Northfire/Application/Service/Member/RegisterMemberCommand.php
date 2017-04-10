<?php

namespace Northfire\Application\Service\Member;

use Northfire\Domain\Model\Member\MemberId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegisterMemberCommand
 *
 * @package Northfire\Application\Service\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
final class RegisterMemberCommand
{
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
    public $firstName;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    public $lastName;
    /**
     * @var int
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    protected $vehicleId;
    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Type(type="datetime")
     */
    protected $joiningDate;
    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Type(type="datetime")
     */
    protected $leavingDate;

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId(): MemberId
    {
        if (null === $this->memberId) {
            $this->memberId = MemberId::generate();
        }

        return $this->memberId;
    }

    /**
     * @return string
     */
    public function memberNumber(): string
    {
        return $this->memberNumber;
    }

    /**
     * @return int
     */
    public function vehicleId(): int
    {
        return $this->vehicleId;
    }

    /**
     * @return \DateTime
     */
    public function joiningDate(): \DateTime
    {
        return $this->joiningDate;
    }

    /**
     * @return \DateTime
     */
    public function leavingDate(): \DateTime
    {
        return $this->leavingDate;
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