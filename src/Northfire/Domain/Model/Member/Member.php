<?php

namespace Northfire\Domain\Model\Member;

use Northfire\Domain\Model\Member\Event\MemberRegistered;
use Northfire\Domain\Model\Member\Event\NameChanged;
use Northfire\Domain\Model\Member\Event\VehicleChanged;
use Prooph\EventSourcing\AggregateRoot;

/**
 * Class Member
 *
 * @package Northfire\Domain\Model
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
class Member extends AggregateRoot
{
    /** @var string */
    protected $firstName;

    /** @var \DateTime */
    protected $joiningDate;

    /** @var string */
    protected $lastName;

    /** @var \DateTime */
    protected $leavingDate;

    /** @var \Northfire\Domain\Model\Member\MemberId */
    protected $memberId;

    /** @var string */
    protected $memberNumber;

    /** @var \Northfire\Domain\Model\Member\VehicleId */
    protected $vehicleId;

    /**
     * @param \Northfire\Domain\Model\Member\MemberId  $aMemberId
     * @param string                                   $aMemberNumber
     * @param string                                   $aFirstName
     * @param string                                   $aLastName
     * @param \Northfire\Domain\Model\Member\VehicleId $aVehicleId
     * @param \DateTime                                $aJoiningDate
     *
     * @return \Northfire\Domain\Model\Member\Member
     */
    public static function registerNew(MemberId $aMemberId, string $aMemberNumber, string $aFirstName, string $aLastName, VehicleId $aVehicleId, \DateTime $aJoiningDate) : self
    {
        $self = new self();
        $self->recordThat(MemberRegistered::withData($aMemberId, $aMemberNumber, $aFirstName, $aLastName, $aVehicleId, $aJoiningDate));

        return $self;
    }

    /**
     * @param string $aFirstName
     * @param string $aLastName
     *
     * @return void
     */
    public function changeName(string $aFirstName, string $aLastName)
    {
        if($this->firstName === $aFirstName && $this->lastName === $aLastName) {
            return;
        }

        $this->recordThat(NameChanged::withData($this->memberId, $aFirstName, $aLastName));
    }

    /**
     * @param \Northfire\Domain\Model\Member\VehicleId $aNewVehicleId
     *
     * @return void
     */
    public function changeVehicle(VehicleId $aNewVehicleId)
    {
        if($this->vehicleId->sameValueAs($aNewVehicleId)) {
            return;
        }

        $this->recordThat(VehicleChanged::withData($this->memberId, $aNewVehicleId, $this->vehicleId()));
    }

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
     * @return \DateTime
     */
    public function leavingDate() : \DateTime
    {
        return $this->leavingDate;
    }

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId() : MemberId
    {
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
     * @return \Northfire\Domain\Model\Member\VehicleId
     */
    public function vehicleId() : VehicleId
    {
        return $this->vehicleId;
    }

    /**
     * @inheritdoc
     */
    protected function aggregateId()
    {
        return $this->memberId;
    }

    /**
     * @param \Northfire\Domain\Model\Member\Event\MemberRegistered $event
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
    }

    /**
     * @param \Northfire\Domain\Model\Member\Event\NameChanged $event
     *
     * @return void
     */
    protected function whenNameChanged(NameChanged $event)
    {
        $this->firstName = $event->firstName();
        $this->lastName = $event->lastName();
    }

    /**
     * @param \Northfire\Domain\Model\Member\Event\VehicleChanged $event
     *
     * @return void
     */
    protected function whenVehicleChanged(VehicleChanged $event)
    {
        $this->vehicleId = $event->newVehicleId();
    }
}