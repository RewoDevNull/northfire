<?php

namespace Northfire\Domain\Model\Member\Handler;

use Northfire\Domain\Model\Member\Command\ChangeName;
use Northfire\Domain\Model\Member\MemberRepositoryInterface;

/**
 * Class ChangeNameHandler
 *
 * @package Northfire\Domain\Model\Member\Handler
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
     * @param \Northfire\Domain\Model\Member\Command\ChangeName $command
     */
    public function __invoke(ChangeName $command)
    {
        $member = $this->memberRepository->get($command->memberId());
        $member->changeName($command->firstName(), $command->lastName());
    }
}