<?php

namespace Northfire\Domain\Model\Member;

/**
 * Interface MemberRepositoryInterface
 *
 * @package Northfire\Domain\Model\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
interface MemberRepositoryInterface
{
    /**
     * @param \Northfire\Domain\Model\Member\Member $member
     *
     * @return void
     */
    public function add(Member $member);

    /**
     * @param \Northfire\Domain\Model\Member\MemberId $memberId
     *
     * @return \Northfire\Domain\Model\Member\Member
     */
    public function get(MemberId $memberId) : Member;
}