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
    /** @var int */
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
     * @param string $firstName
     * @param string $lastName
     *
     * @return void
     */
    public function changeName(string $firstName, string $lastName)
    {
        $this->recordThat(NameChanged::withData($this->memberId, $firstName, $lastName));
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