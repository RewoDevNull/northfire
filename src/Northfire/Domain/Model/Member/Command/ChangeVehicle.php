<?php

namespace Northfire\Domain\Model\Member\Command;

use Northfire\Domain\Model\Member\MemberId;
use Northfire\Domain\Model\Member\VehicleId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangeVehicleCommand
 *
 * @package Northfire\Domain\Model\Member\Handler
 * @author  Hauke Weber
 */
final class ChangeVehicle
{
    /** @var string */
    public $memberId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    public $vehicleId;

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId(): MemberId
    {
        return MemberId::fromString($this->memberId);
    }

    /**
     * @return \Northfire\Domain\Model\Member\VehicleId
     */
    public function vehicleId(): VehicleId
    {
        return VehicleId::fromString($this->vehicleId);
    }
}