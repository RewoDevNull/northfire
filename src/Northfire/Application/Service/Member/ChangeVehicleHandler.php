<?php

namespace Northfire\Application\Service\Member;

use Northfire\Domain\Model\Member\MemberRepositoryInterface;

/**
 * Class ChangeVehicleHandler
 *
 * @package Northfire\Application\Service\Member
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
     * @param \Northfire\Application\Service\Member\ChangeVehicleCommand $command
     */
    public function __invoke(ChangeVehicleCommand $command)
    {
        $member = $this->memberRepository->get($command->memberId());
        $member->changeVehicle($command->vehicleId());
    }
}