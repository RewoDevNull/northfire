<?php

namespace Northfire\Domain\Model\Member;

use Prooph\EventSourcing\AggregateChanged;

/**
 * Class MemberRegistered
 *
 * @package Northfire\Domain\Model\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
final class MemberRegistered extends AggregateChanged
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

    /**
     * @param \Northfire\Domain\Model\Member\MemberId  $memberId
     * @param string                                   $memberNumber
     * @param string                                   $firstName
     * @param string                                   $lastName
     * @param \Northfire\Domain\Model\Member\VehicleId $vehicleId
     * @param \DateTime                                $joiningDate
     *
     * @return \Northfire\Domain\Model\Member\MemberRegistered
     */
    public static function withData(MemberId $memberId, string $memberNumber, string $firstName, string $lastName, VehicleId $vehicleId, \DateTime $joiningDate)
    {
        $event = self::occur(
            $memberId->toString(),
            [
                'memberNumber' => $memberNumber,
                'firstName'    => $firstName,
                'lastName'     => $lastName,
                'vehicleId'    => $vehicleId->toString(),
                'joiningDate'  => $joiningDate->format('d.m.Y H:i:s')
            ]
        );

        $event->memberId = $memberId;
        $event->memberNumber = $memberNumber;
        $event->firstName = $firstName;
        $event->lastName = $lastName;
        $event->vehicleId = $vehicleId;
        $event->joiningDate = $joiningDate;

        return $event;
    }

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId(): MemberId
    {
        if (null === $this->memberId) {
            $this->memberId = MemberId::fromString($this->aggregateId());
        }

        return $this->memberId;
    }

    /**
     * @return string
     */
    public function memberNumber(): string
    {
        if (null === $this->memberNumber) {
            $this->memberNumber = $this->payload['memberNumber'];
        }

        return $this->memberNumber;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        if (null === $this->firstName) {
            $this->firstName = $this->payload['firstName'];
        }

        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        if (null === $this->lastName) {
            $this->lastName = $this->payload['lastName'];
        }

        return $this->lastName;
    }

    /**
     * @return \Northfire\Domain\Model\Member\VehicleId
     */
    public function vehicleId(): VehicleId
    {
        if (null === $this->vehicleId) {
            $this->vehicleId = VehicleId::fromString($this->payload['vehicleId']);
        }

        return $this->vehicleId;
    }

    /**
     * @return \DateTime
     */
    public function joiningDate(): \DateTime
    {
        if (null === $this->joiningDate) {
            $this->joiningDate = \DateTime::createFromFormat('d.m.Y H:i:s', $this->payload['joiningDate']);
        }

        return $this->joiningDate;
    }
}