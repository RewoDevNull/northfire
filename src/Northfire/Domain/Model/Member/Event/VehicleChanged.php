<?php

namespace Northfire\Domain\Model\Member\Event;

use Northfire\Domain\Model\Member\MemberId;
use Northfire\Domain\Model\Member\VehicleId;
use Prooph\EventSourcing\AggregateChanged;

/**
 * Class VehicleChanged
 *
 * @package Northfire\Domain\Model\Member
 * @author  Hauke Weber
 */
final class VehicleChanged extends AggregateChanged
{
    /** @var \Northfire\Domain\Model\Member\MemberId */
    protected $memberId;

    /** @var \Northfire\Domain\Model\Member\VehicleId */
    protected $newVehicleId;

    /** @var \Northfire\Domain\Model\Member\VehicleId */
    protected $oldVehicleId;

    /**
     * @param \Northfire\Domain\Model\Member\MemberId  $memberId
     * @param \Northfire\Domain\Model\Member\VehicleId $newVehicleId
     * @param \Northfire\Domain\Model\Member\VehicleId $oldVehicleId
     *
     * @return \Northfire\Domain\Model\Member\Event\VehicleChanged
     */
    public static function withData(MemberId $memberId, VehicleId $newVehicleId, VehicleId $oldVehicleId) : self
    {
        $event = self::occur($memberId->toString(), [
            'oldVehicleId' => $oldVehicleId->toString(),
            'newVehicleId' => $newVehicleId->toString()
        ]);

        $event->oldVehicleId = $oldVehicleId;
        $event->newVehicleId = $newVehicleId;

        return $event;
    }

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId() : MemberId
    {
        if (null === $this->memberId) {
            $this->memberId = MemberId::fromString($this->payload['memberId']);
        }

        return $this->memberId;
    }

    /**
     * @return \Northfire\Domain\Model\Member\VehicleId
     */
    public function newVehicleId() : VehicleId
    {
        if (null === $this->newVehicleId) {
            $this->newVehicleId = VehicleId::fromString($this->payload['newVehicleId']);
        }

        return $this->newVehicleId;
    }

    /**
     * @return \Northfire\Domain\Model\Member\VehicleId
     */
    public function oldVehicleId() : VehicleId
    {
        if (null === $this->oldVehicleId) {
            $this->oldVehicleId = VehicleId::fromString($this->payload['oldVehicleId']);
        }

        return $this->oldVehicleId;
    }
}