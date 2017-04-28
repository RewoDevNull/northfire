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
        $event = $recordedEvents[1];

        $this->assertCount(2, $recordedEvents);
        $this->assertInstanceOf(NameChanged::class, $event);
        $this->assertEquals(2, $aggregateRootVersion);

        $this->assertEquals($member->memberId()->toString(), $event->aggregateId());
        $this->assertCount(2, $event->payload());
        $this->assertArrayHasKey('firstName', $event->payload());
        $this->assertArrayHasKey('lastName', $event->payload());

        $this->assertEquals('Lieschen', $event->payload()['firstName']);
        $this->assertEquals('Müller', $event->payload()['lastName']);
    }

    /**
     * Test the member domain model for recording the MemberRegistered event.
     */
    public function testMemberRegistered()
    {
        $member = $this->createMember();

        $aggregateRootVersion = TestUtil::aggregateRootDecorator()->extractAggregateVersion($member);
        $recordedEvents = TestUtil::aggregateRootDecorator()->extractRecordedEvents($member);
        $event = $recordedEvents[0];

        $this->assertCount(1, $recordedEvents);
        $this->assertInstanceOf(MemberRegistered::class, $event);
        $this->assertEquals(1, $aggregateRootVersion);

        $this->assertEquals($member->memberId()->toString(), $event->aggregateId());
        $this->assertCount(5, $event->payload());
        $this->assertArrayHasKey('memberNumber', $event->payload());
        $this->assertArrayHasKey('firstName', $event->payload());
        $this->assertArrayHasKey('lastName', $event->payload());
        $this->assertArrayHasKey('vehicleId', $event->payload());
        $this->assertArrayHasKey('joiningDate', $event->payload());

        $this->assertEquals('01-00', $event->payload()['memberNumber']);
        $this->assertEquals('Max', $event->payload()['firstName']);
        $this->assertEquals('Mustermann', $event->payload()['lastName']);
        $this->assertEquals($member->vehicleId()->toString(), $event->payload()['vehicleId']);
        $this->assertEquals($member->joiningDate()->format('d.m.Y H:i:s'), $event->payload()['joiningDate']);
    }

    /**
     * Test the member domain model for recording the NameChanged event.
     */
    public function testMemberVehicleChanged()
    {
        $member = $this->createMember();

        $oldVehicleId = $member->vehicleId();
        $newVehicleId = VehicleId::generate();

        $member->changeVehicle($newVehicleId);

        $aggregateRootVersion = TestUtil::aggregateRootDecorator()->extractAggregateVersion($member);
        $recordedEvents = TestUtil::aggregateRootDecorator()->extractRecordedEvents($member);
        $event = $recordedEvents[1];

        $this->assertCount(2, $recordedEvents);
        $this->assertInstanceOf(VehicleChanged::class, $event);
        $this->assertEquals(2, $aggregateRootVersion);

        $this->assertEquals($member->memberId()->toString(), $event->aggregateId());
        $this->assertCount(2, $event->payload());
        $this->assertArrayHasKey('oldVehicleId', $event->payload());
        $this->assertArrayHasKey('newVehicleId', $event->payload());

        $this->assertEquals($oldVehicleId->toString(), $event->payload()['oldVehicleId']);
        $this->assertEquals($newVehicleId->toString(), $event->payload()['newVehicleId']);
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