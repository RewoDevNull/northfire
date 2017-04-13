<?php

namespace Northfire\Application\Service\Member;

use Northfire\Domain\Model\Member\Member;
use Northfire\Domain\Model\Member\VehicleChanged;
use Northfire\Domain\Model\Member\VehicleId;
use Northfire\Infrastructure\Persistence\Repository\EventStoreMemberRepository;
use Northfire\Infrastructure\Utils\TestUtil;
use PHPUnit\Framework\TestCase;

/**
 * Class VehicleChangedTest
 *
 * @package Northfire\Application\Service\Member
 * @author  Hauke Weber
 */
class VehicleChangedTest extends TestCase
{
    /**
     * Test the command bus for recording a VehicleChanged event onto the member aggregate root
     */
    public function testCommandBusMemberVehicleChanged()
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
        $changeVehicleHandler = new ChangeVehicleHandler($memberRepository);

        $commandBus = TestUtil::createCommandBus([
            RegisterMemberCommand::class => $registerMemberHandler,
            ChangeVehicleCommand::class  => $changeVehicleHandler
        ]);
        $commandBus->utilize($transactionManager);

        $oldVehicleId = VehicleId::generate();
        $newVehicleId = VehicleId::generate();

        $command = new RegisterMemberCommand();
        $command->memberNumber = '01-00';
        $command->firstName = 'Max';
        $command->lastName = 'Mustermann';
        $command->vehicleId = $oldVehicleId->toString();
        $command->joiningDate = new \DateTime();

        $commandBus->dispatch($command);

        $recordedEvents = TestUtil::retrieveRecordedEvents($eventStore, 'member_stream');
        $memberId = $recordedEvents[0]->aggregateId();

        $command = new ChangeVehicleCommand();
        $command->memberId = $memberId;
        $command->vehicleId = $newVehicleId->toString();

        $commandBus->dispatch($command);

        /** @var VehicleChanged $event */
        $event = TestUtil::retrieveRecordedEvents($eventStore, 'member_stream')[0];

        $this->assertInstanceOf(VehicleChanged::class, $event);

        $this->assertEquals($memberId, $event->aggregateId());
        $this->assertCount(2, $event->payload());
        $this->assertArrayHasKey('oldVehicleId', $event->payload());
        $this->assertArrayHasKey('newVehicleId', $event->payload());

        $this->assertEquals($memberId, $event->aggregateId());
        $this->assertEquals($oldVehicleId->toString(), $event->oldVehicleId()->toString());
        $this->assertEquals($newVehicleId->toString(), $event->newVehicleId()->toString());
    }
}