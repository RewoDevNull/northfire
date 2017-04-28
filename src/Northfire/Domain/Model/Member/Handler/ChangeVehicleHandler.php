<?php

namespace Northfire\Domain\Model\Member\Handler;

use Northfire\Domain\Model\Member\Command\ChangeVehicle;
use Northfire\Domain\Model\Member\MemberRepositoryInterface;

/**
 * Class ChangeVehicleHandler
 *
 * @package Northfire\Domain\Model\Member\Handler
 * @author  Hauke Weber
 */
final class ChangeVehicleHandler
{
    /** @var \Northfire\Domain\Model\Member\MemberRepositoryInterface */
    private $memberRepository;

    /**
     * ChangeVehicleHandler constructor.
     *
     * @param \Northfire\Domain\Model\Member\MemberRepositoryInterface $memberRepository
     */
    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * @param \Northfire\Domain\Model\Member\Command\ChangeVehicle $command
     */
    public function __invoke(ChangeVehicle $command)
    {
        $member = $this->memberRepository->get($command->memberId());
        $member->changeVehicle($command->vehicleId());
    }
}