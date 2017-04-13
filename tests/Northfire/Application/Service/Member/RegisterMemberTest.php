<?php

namespace Northfire\Application\Service\Member;

use Northfire\Domain\Model\Member\Member;
use Northfire\Domain\Model\Member\MemberRegistered;
use Northfire\Domain\Model\Member\VehicleId;
use Northfire\Infrastructure\Persistence\Repository\EventStoreMemberRepository;
use Northfire\Infrastructure\Utils\TestUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class RegisterMemberTest
 *
 * @package Northfire\Application\Service\Member
 * @author  Hauke Weber
 */
class RegisterMemberTest extends TestCase
{
    /**
     * Test the command bus for recording a MemberRegistered event onto the member aggregate root
     */
    public function testCommandMemberRegistered()
    {
        /** @var EventStoreMemberRepository $memberRepository */
        $eventStore = TestUtil::createEventStore();
        $memberRepository = TestUtil::createEventStoreRepository(
            EventStoreMemberRepository::class,
            $eventStore,
            Member::class,
            'member_stream'
        );
        $transactionManager = TestUtil::createTransactionManager($eventStore);

        // create and declare the command
        $command = new RegisterMemberCommand();
        $command->memberNumber = '01-00';
        $command->firstName = 'Max';
        $command->lastName = 'Mustermann';
        $command->vehicleId = VehicleId::generate()->toString();
        $command->joiningDate = new \DateTime();
        $commandHandler = new RegisterMemberHandler($memberRepository);

        // create a command bus and dispatch the command away
        $commandBus = TestUtil::createCommandBus([RegisterMemberCommand::class => $commandHandler]);
        $commandBus->utilize($transactionManager);
        $commandBus->dispatch($command);

        $recordedEvents = TestUtil::retrieveRecordedEvents($eventStore, 'member_stream');
        $this->assertCount(1, $recordedEvents);

        /** @var MemberRegistered $event */
        $event = $recordedEvents[0];

        $this->assertInstanceOf(MemberRegistered::class, $event);

        $this->assertCount(5, $event->payload());
        $this->assertArrayHasKey('memberNumber', $event->payload());
        $this->assertArrayHasKey('firstName', $event->payload());
        $this->assertArrayHasKey('lastName', $event->payload());
        $this->assertArrayHasKey('vehicleId', $event->payload());
        $this->assertArrayHasKey('joiningDate', $event->payload());

        $this->assertEquals('01-00', $event->payload()['memberNumber']);
        $this->assertEquals('Max', $event->payload()['firstName']);
        $this->assertEquals('Mustermann', $event->payload()['lastName']);
        $this->assertEquals($command->vehicleId, $event->payload()['vehicleId']);
        $this->assertEquals($command->joiningDate->format('d.m.Y H:i:s'), $event->payload()['joiningDate']);

        $this->assertEquals($event->payload()['memberNumber'], $event->memberNumber());
        $this->assertEquals($event->payload()['firstName'], $event->firstName());
        $this->assertEquals($event->payload()['lastName'], $event->lastName());
        $this->assertEquals($event->payload()['vehicleId'], $event->vehicleId()->toString());
        $this->assertEquals($event->payload()['joiningDate'], $event->joiningDate()->format('d.m.Y H:i:s'));
    }
}