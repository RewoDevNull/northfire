<?php

namespace Northfire\Domain\Model\Member;

use Northfire\Domain\Model\IdentifiableInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class MemberId
 *
 * @package Northfire\Domain\Model
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
class MemberId implements IdentifiableInterface
{
    /** @var \Ramsey\Uuid\UuidInterface */
    private $uuid;

    /**
     * MemberId constructor.
     *
     * @param \Ramsey\Uuid\UuidInterface|\Ramsey\Uuid\Uuid $uuid
     */
    private function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return $this->toString();
    }

    /**
     * @param string $memberId
     *
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public static function fromString(string $memberId) : self
    {
        return new self(Uuid::fromString($memberId));
    }

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public static function generate() : self
    {
        return new self(Uuid::uuid4());
    }

    /**
     * @param \Northfire\Domain\Model\Member\MemberId $other
     *
     * @return bool
     */
    public function sameValueAs(MemberId $other) : bool
    {
        return ($this->toString() === $other->toString());
    }

    /**
     * @return string
     */
    public function toString() : string
    {
        return $this->uuid->toString();
    }
}