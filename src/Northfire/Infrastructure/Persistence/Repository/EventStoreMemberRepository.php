<?php

namespace Northfire\Infrastructure\Persistence\Repository;

use Northfire\Domain\Model\Member\Member;
use Northfire\Domain\Model\Member\MemberId;
use Northfire\Domain\Model\Member\MemberRepositoryInterface;
use Prooph\EventStore\Aggregate\AggregateRepository;

/**
 * Class EventStoreMemberRepository
 *
 * @package Northfire\Infrastructure\Persistence\Repository
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
class EventStoreMemberRepository extends AggregateRepository implements MemberRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function add(Member $member)
    {
        $this->addAggregateRoot($member);
    }

    /**
     * @inheritdoc
     * @return \Northfire\Domain\Model\Member\Member|\stdClass|null
     */
    public function get(MemberId $memberId) : Member
    {
        return $this->getAggregateRoot($memberId->toString());
    }

    /**
     * @inheritdoc
     */
    public function version(Member $member) : int
    {
        return $this->extractAggregateVersion($member);
    }

}