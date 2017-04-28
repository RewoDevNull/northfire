<?php

namespace Northfire\Domain\Model\Member\Handler;

use Northfire\Domain\Model\Member\Command\RegisterMember;
use Northfire\Domain\Model\Member\Member;
use Northfire\Domain\Model\Member\MemberRepositoryInterface;
use Northfire\Domain\Model\Member\VehicleId;

/**
 * Class RegisterMemberHandler
 *
 * @package Northfire\Domain\Model\Member\Handler
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
     * @param \Northfire\Domain\Model\Member\Command\RegisterMember $command
     */
    public function __invoke(RegisterMember $command)
    {
        $member = Member::registerNew(
            $command->memberId(),
            $command->memberNumber(),
            $command->firstName(),
            $command->lastName(),
            VehicleId::fromString($command->vehicleId()),
            $command->joiningDate()
        );

        $this->memberRepository->add($member);
    }
}