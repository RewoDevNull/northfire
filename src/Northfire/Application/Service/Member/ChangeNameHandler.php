<?php

namespace Northfire\Application\Service\Member;

use Northfire\Domain\Model\Member\MemberRepositoryInterface;

/**
 * Class ChangeNameHandler
 *
 * @package Northfire\Application\Service\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
final class ChangeNameHandler
{
    /** @var \Northfire\Domain\Model\Member\MemberRepositoryInterface */
    private $memberRepository;

    /**
     * ChangeNameHandler constructor.
     *
     * @param \Northfire\Domain\Model\Member\MemberRepositoryInterface $memberRepository
     */
    public function __construct(MemberRepositoryInterface $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * @param \Northfire\Application\Service\Member\ChangeNameCommand $command
     */
    public function __invoke(ChangeNameCommand $command)
    {
        $member = $this->memberRepository->get($command->memberId());
        $member->changeName($command->firstName(), $command->lastName());
    }
}