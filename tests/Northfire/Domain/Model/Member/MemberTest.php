<?php

namespace Northfire\Domain\Model\Member;

use Northfire\Domain\Model\Member\Event\MemberRegistered;
use Northfire\Domain\Model\Member\Event\NameChanged;
use Northfire\Domain\Model\Member\Event\VehicleChanged;
use Northfire\Infrastructure\Utils\TestUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class MemberTest
 *
 * @package Northfire\Domain\Model\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
class MemberTest extends TestCase
{
    /**
     * Test the member domain model for recording the NameChanged event.
     */
    public function testMemberNameChanged()
    {
        $member = $this->createMember();
        $member->changeName('Lieschen', 'Müller');

        $aggregateRootVersion = TestUtil::aggregateRootDecorator()->extractAggregateVersion($member);
        $recordedEvents = TestUtil::aggregateRootDecorator()->extractRecordedEvents($member);

        $this->assertCount(2, $recordedEvents);
        $this->assertEquals(2, $aggregateRootVersion);

        /** @var \Northfire\Domain\Model\Member\Event\NameChanged $event */
        $event = $recordedEvents[1];

        $this->assertInstanceOf(NameChanged::class, $event);
        $this->assertEquals($member->memberId()->toString(), $event->aggregateId());
        $this->assertCount(2, $event->payload());
        $this->assertArrayHasKey('firstName', $event->payload());
        $this->assertArrayHasKey('lastName', $event->payload());

        $this->assertEquals('Lieschen', $event->payload()['firstName']);
        $this->assertEquals('Müller', $event->payload()['lastName']);

        $this->assertEquals($event->payload()['firstName'], $event->firstName());
        $this->assertEquals($event->payload()['lastName'], $event->lastName());
    }

    /**
     * Test the member domain model for recording the MemberRegistered event.
     */
    public function testMemberRegistered()
    {
        $member = $this->createMember();

        $memberId = $member->memberId();
        $vehicleId = $member->vehicleId();

        $aggregateRootVersion = TestUtil::aggregateRootDecorator()->extractAggregateVersion($member);
        $recordedEvents = TestUtil::aggregateRootDecorator()->extractRecordedEvents($member);

        $this->assertCount(1, $recordedEvents);
        $this->assertEquals(1, $aggregateRootVersion);

        /** @var \Northfire\Domain\Model\Member\Event\MemberRegistered $event */
        $event = $recordedEvents[0];

        $this->assertInstanceOf(MemberRegistered::class, $event);

        // test if payload has all necessary keys
        $this->assertCount(5, $event->payload());
        $this->assertArrayHasKey('memberNumber', $event->payload());
        $this->assertArrayHasKey('firstName', $event->payload());
        $this->assertArrayHasKey('lastName', $event->payload());
        $this->assertArrayHasKey('vehicleId', $event->payload());
        $this->assertArrayHasKey('joiningDate', $event->payload());

        // test if payload data matches the given data
        $this->assertEquals($memberId->toString(), $event->aggregateId());
        $this->assertEquals('01-00', $event->payload()['memberNumber']);
        $this->assertEquals('Max', $event->payload()['firstName']);
        $this->assertEquals('Mustermann', $event->payload()['lastName']);
        $this->assertEquals($vehicleId->toString(), $event->payload()['vehicleId']);
        $this->assertEquals($member->joiningDate()->format('d.m.Y H:i:s'), $event->payload()['joiningDate']);

        // test if payload data and getMethods return the same data
        $this->assertEquals($event->payload()['memberNumber'], $event->memberNumber());
        $this->assertEquals($event->payload()['firstName'], $event->firstName());
        $this->assertEquals($event->payload()['lastName'], $event->lastName());
        $this->assertEquals($event->payload()['vehicleId'], $event->vehicleId()->toString());
        $this->assertEquals($event->payload()['joiningDate'], $event->joiningDate()->format('d.m.Y H:i:s'));
    }

    /**
     * Test the member domain model for recording the NameChanged event.
     */
    public function testMemberVehicleChanged()
    {
        $member = $this->createMember();

        $memberId = $member->memberId();
        $oldVehicleId = $member->vehicleId();
        $newVehicleId = VehicleId::generate();

        $member->changeVehicle($newVehicleId);

        $aggregateRootVersion = TestUtil::aggregateRootDecorator()->extractAggregateVersion($member);
        $recordedEvents = TestUtil::aggregateRootDecorator()->extractRecordedEvents($member);

        $this->assertCount(2, $recordedEvents);
        $this->assertEquals(2, $aggregateRootVersion);

        /** @var \Northfire\Domain\Model\Member\Event\VehicleChanged $event */
        $event = $recordedEvents[1];

        $this->assertInstanceOf(VehicleChanged::class, $event);

        $this->assertCount(2, $event->payload());
        $this->assertArrayHasKey('oldVehicleId', $event->payload());
        $this->assertArrayHasKey('newVehicleId', $event->payload());

        $this->assertEquals($memberId->toString(), $event->aggregateId());
        $this->assertEquals($oldVehicleId->toString(), $event->payload()['oldVehicleId']);
        $this->assertEquals($newVehicleId->toString(), $event->payload()['newVehicleId']);

        $this->assertEquals($event->payload()['oldVehicleId'], $event->oldVehicleId()->toString());
        $this->assertEquals($event->payload()['newVehicleId'], $event->newVehicleId()->toString());
    }

    /**
     * @return \Northfire\Domain\Model\Member\Member
     */
    private function createMember() : Member
    {
        $memberId = MemberId::generate();
        $vehicleId = VehicleId::generate();
        $joiningDate = new \DateTime();

        return Member::registerNew($memberId, '01-00', 'Max', 'Mustermann', $vehicleId, $joiningDate);
    }
}