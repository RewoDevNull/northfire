<?php

namespace Northfire\Application\Service\Member;

use Northfire\Domain\Model\Member\Member;
use Northfire\Domain\Model\Member\MemberRepositoryInterface;
use Northfire\Domain\Model\Member\VehicleId;

/**
 * Class RegisterMemberHandler
 *
 * @package Northfire\Application\Service\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
final class RegisterMemberHandler
{
    /** @var \Northfire\Domain\Model\Member\MemberRepositoryInterface */
    private $memberRepository;

    /**
     * RegisterMemberHandler constructor.
     *
     * @param \Northfire\Domain\Model\Member\MemberRepositoryInterface $memberRepository
     */
    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * @param \Northfire\Application\Service\Member\RegisterMemberCommand $command
     */
    public function __invoke(RegisterMemberCommand $command)
    {
        $member = Member::registerNew(
            $command->memberId(),
            $command->memberNumber(),
            $command->firstName(),
            $command->lastName(),
            VehicleId::fromString($command->vehicleId()),
            $command->joiningDate(),
            $command->leavingDate()
        );

        $this->memberRepository->add($member);
    }
}