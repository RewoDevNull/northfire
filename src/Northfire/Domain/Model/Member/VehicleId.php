<?php

namespace Northfire\Domain\Model\Member;

use Northfire\Domain\Model\IdentifiableInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class VehicleId
 *
 * @package Northfire\Domain\Model\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
class VehicleId implements IdentifiableInterface
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
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @return \Northfire\Domain\Model\Member\VehicleId
     */
    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    /**
     * @param string $vehicleId
     *
     * @return \Northfire\Domain\Model\Member\VehicleId
     */
    public static function fromString(string $vehicleId): self
    {
        return new self(Uuid::fromString($vehicleId));
    }

    /**
     * @param \Northfire\Domain\Model\Member\VehicleId $other
     *
     * @return bool
     */
    public function sameValueAs(VehicleId $other): bool
    {
        return ($this->toString() === $other->toString());
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->uuid->toString();
    }
}