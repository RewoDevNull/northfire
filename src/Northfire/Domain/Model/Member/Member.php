<?php

namespace Northfire\Domain\Model\Member;

use Prooph\EventSourcing\AggregateRoot;

/**
 * Class Member
 *
 * @package Northfire\Domain\Model
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
class Member extends AggregateRoot
{
    /** @var \Northfire\Domain\Model\Member\MemberId */
    protected $memberId;
    /** @var string */
    protected $memberNumber;
    /** @var string */
    protected $firstName;
    /** @var string */
    protected $lastName;
    /** @var \Northfire\Domain\Model\Member\VehicleId */
    protected $vehicleId;
    /** @var \DateTime */
    protected $joiningDate;
    /** @var \DateTime */
    protected $leavingDate;

    /**
     * @inheritdoc
     */
    protected function aggregateId()
    {
        return $this->memberId;
    }

    /**
     * @param \Northfire\Domain\Model\Member\MemberId  $aMemberId
     * @param string                                   $aMemberNumber
     * @param string                                   $aFirstName
     * @param string                                   $aLastName
     * @param \Northfire\Domain\Model\Member\VehicleId $aVehicleId
     * @param \DateTime                                $aJoiningDate
     * @param \DateTime                                $aLeavingDate
     *
     * @return \Northfire\Domain\Model\Member\Member
     */
    public static function registerNew(MemberId $aMemberId, string $aMemberNumber, string $aFirstName, string $aLastName, VehicleId $aVehicleId, \DateTime $aJoiningDate, \DateTime $aLeavingDate): self
    {
        $self = new self();
        $self->recordThat(MemberRegistered::withData($aMemberId, $aMemberNumber, $aFirstName, $aLastName, $aVehicleId, $aJoiningDate, $aLeavingDate));

        return $self;
    }

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId(): MemberId
    {
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

    /**
     * @return \Northfire\Domain\Model\Member\VehicleId
     */
    public function vehicleId(): VehicleId
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
     * @param string $aFirstName
     * @param string $aLastName
     *
     * @return void
     */
    public function changeName(string $aFirstName, string $aLastName)
    {
        $this->recordThat(NameChanged::withData($this->memberId, $aFirstName, $aLastName));
    }

    /**
     * @param \Northfire\Domain\Model\Member\MemberRegistered $event
     *
     * @return void
     */
    protected function whenMemberRegistered(MemberRegistered $event)
    {
        $this->memberId = $event->memberId();
        $this->memberNumber = $event->memberNumber();
        $this->firstName = $event->firstName();
        $this->lastName = $event->lastName();
        $this->vehicleId = $event->vehicleId();
        $this->joiningDate = $event->joiningDate();
        $this->leavingDate = $event->leavingDate();
    }

    /**
     * @param \Northfire\Domain\Model\Member\NameChanged $event
     *
     * @return void
     */
    protected function whenNameChanged(NameChanged $event)
    {
        $this->firstName = $event->firstName();
        $this->lastName = $event->lastName();
    }
}