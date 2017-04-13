<?php

namespace Northfire\Application\Service\Member;

use Northfire\Domain\Model\Member\Member;
use Northfire\Domain\Model\Member\NameChanged;
use Northfire\Domain\Model\Member\VehicleId;
use Northfire\Infrastructure\Persistence\Repository\EventStoreMemberRepository;
use Northfire\Infrastructure\Utils\TestUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class NameChangedTest
 *
 * @package Northfire\Application\Service\Member
 * @author  Hauke Weber
 */
class NameChangedTest extends TestCase
{
    /**
     * Test the command bus for recording a NameChanged event onto the member aggregate root
     */
    public function testCommandBusMemberNameChanged()
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

        $registerMemberHandler = new RegisterMemberHandler($memberRepository);
        $changeNameHandler = new ChangeNameHandler($memberRepository);

        $commandBus = TestUtil::createCommandBus([
            RegisterMemberCommand::class => $registerMemberHandler,
            ChangeNameCommand::class     => $changeNameHandler
        ]);
        $commandBus->utilize($transactionManager);

        $command = new RegisterMemberCommand();
        $command->memberNumber = '01-00';
        $command->firstName = 'Max';
        $command->lastName = 'Mustermann';
        $command->vehicleId = VehicleId::generate()->toString();
        $command->joiningDate = new \DateTime();

        $commandBus->dispatch($command);

        $recordedEvents = TestUtil::retrieveRecordedEvents($eventStore, 'member_stream');
        $memberId = $recordedEvents[0]->aggregateId();

        $command = new ChangeNameCommand();
        $command->memberId = $memberId;
        $command->firstName = 'Lieschen';
        $command->lastName = 'Müller';

        $commandBus->dispatch($command);

        $recordedEvents = TestUtil::retrieveRecordedEvents($eventStore, 'member_stream');
        $this->assertCount(1, $recordedEvents);

        /** @var NameChanged $event */
        $event = $recordedEvents[0];

        $this->assertInstanceOf(NameChanged::class, $event);

        $this->assertEquals($memberId, $event->aggregateId());
        $this->assertCount(2, $event->payload());
        $this->assertArrayHasKey('firstName', $event->payload());
        $this->assertArrayHasKey('lastName', $event->payload());

        $this->assertEquals($memberId, $event->aggregateId());
        $this->assertEquals('Lieschen', $event->payload()['firstName']);
        $this->assertEquals('Müller', $event->payload()['lastName']);
        $this->assertEquals($event->payload()['firstName'], $event->firstName());
        $this->assertEquals($event->payload()['lastName'], $event->lastName());
    }
}