<?php

namespace Northfire\Domain\Model\Member;

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
    public function testDomainModelMemberNameChanged()
    {
        $member = $this->createMember();
        $member->changeName('Lieschen', 'Müller');

        $aggregateRootVersion = TestUtil::aggregateRootDecorator()->extractAggregateVersion($member);
        $recordedEvents = TestUtil::aggregateRootDecorator()->extractRecordedEvents($member);

        $this->assertCount(2, $recordedEvents);
        $this->assertInstanceOf(NameChanged::class, $recordedEvents[1]);
        $this->assertEquals(2, $aggregateRootVersion);

        $this->assertEquals($member->memberId()->toString(), $recordedEvents[1]->aggregateId());
        $this->assertCount(2, $recordedEvents[1]->payload());
        $this->assertArrayHasKey('firstName', $recordedEvents[1]->payload());
        $this->assertArrayHasKey('lastName', $recordedEvents[1]->payload());

        $this->assertEquals('Lieschen', $recordedEvents[1]->payload()['firstName']);
        $this->assertEquals('Müller', $recordedEvents[1]->payload()['lastName']);
    }

    /**
     * Test the member domain model for recording the MemberRegistered event.
     */
    public function testDomainModelMemberRegistered()
    {
        $member = $this->createMember();

        $aggregateRootVersion = TestUtil::aggregateRootDecorator()->extractAggregateVersion($member);
        $recordedEvents = TestUtil::aggregateRootDecorator()->extractRecordedEvents($member);

        $this->assertCount(1, $recordedEvents);
        $this->assertInstanceOf(MemberRegistered::class, $recordedEvents[0]);
        $this->assertEquals(1, $aggregateRootVersion);

        $this->assertEquals($member->memberId()->toString(), $recordedEvents[0]->aggregateId());
        $this->assertCount(5, $recordedEvents[0]->payload());
        $this->assertArrayHasKey('memberNumber', $recordedEvents[0]->payload());
        $this->assertArrayHasKey('firstName', $recordedEvents[0]->payload());
        $this->assertArrayHasKey('lastName', $recordedEvents[0]->payload());
        $this->assertArrayHasKey('vehicleId', $recordedEvents[0]->payload());
        $this->assertArrayHasKey('joiningDate', $recordedEvents[0]->payload());

        $this->assertEquals('01-00', $recordedEvents[0]->payload()['memberNumber']);
        $this->assertEquals('Max', $recordedEvents[0]->payload()['firstName']);
        $this->assertEquals('Mustermann', $recordedEvents[0]->payload()['lastName']);
        $this->assertEquals($member->vehicleId()->toString(), $recordedEvents[0]->payload()['vehicleId']);
        $this->assertEquals($member->joiningDate()->format('d.m.Y H:i:s'), $recordedEvents[0]->payload()['joiningDate']);
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