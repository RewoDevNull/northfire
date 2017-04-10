<?php

namespace Northfire\Domain\Model\Member;

use PHPUnit\Framework\TestCase;
use Prooph\EventSourcing\EventStoreIntegration\AggregateRootDecorator;

/**
 * Class MemberTest
 *
 * @package Northfire\Domain\Model\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
class MemberTest extends TestCase
{
    /**
     * Test the member domain model for recording the MemberRegistered event.
     */
    public function test_newMemberRegistered()
    {
        // stolen from 'ProophTest\EventSourcing\AggregateRootTest'
        $decorator = AggregateRootDecorator::newInstance();

        $memberId = MemberId::generate();
        $vehicleId = VehicleId::generate();
        $joiningDate = new \DateTime();

        $member = Member::registerNew($memberId, '01-00', 'Max', 'Mustermann', $vehicleId, $joiningDate);

        $aggregateRootId = $decorator->extractAggregateId($member);
        $aggregateRootVersion = $decorator->extractAggregateVersion($member);
        $recordedEvents = $decorator->extractRecordedEvents($member);

        $this->assertCount(1, $recordedEvents);
        $this->assertInstanceOf(MemberRegistered::class, $recordedEvents[0]);
        $this->assertEquals($memberId->toString(), $aggregateRootId);
        $this->assertEquals(1, $aggregateRootVersion);

        $this->assertArrayHasKey('memberNumber', $recordedEvents[0]->payload());
        $this->assertArrayHasKey('firstName', $recordedEvents[0]->payload());
        $this->assertArrayHasKey('lastName', $recordedEvents[0]->payload());
        $this->assertArrayHasKey('vehicleId', $recordedEvents[0]->payload());
        $this->assertArrayHasKey('joiningDate', $recordedEvents[0]->payload());

        $this->assertEquals('01-00', $recordedEvents[0]->payload()['memberNumber']);
        $this->assertEquals('Max', $recordedEvents[0]->payload()['firstName']);
        $this->assertEquals('Mustermann', $recordedEvents[0]->payload()['lastName']);
        $this->assertEquals($vehicleId->toString(), $recordedEvents[0]->payload()['vehicleId']);
        $this->assertEquals($joiningDate->format('d.m.Y H:i:s'), $recordedEvents[0]->payload()['joiningDate']);
    }
}