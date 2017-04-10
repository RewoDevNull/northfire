<?php

namespace Northfire\Domain\Model\Member;

use Prooph\EventSourcing\AggregateChanged;

/**
 * Class NameChanged
 *
 * @package Northfire\Domain\Model\Member
 * @author  Hauke Weber <h.weber@rewotec.net>
 */
final class NameChanged extends AggregateChanged
{
    /** @var \Northfire\Domain\Model\Member\MemberId */
    protected $memberId;
    /** @var string */
    protected $firstName;
    /** @var string */
    protected $lastName;

    /**
     * @param \Northfire\Domain\Model\Member\MemberId $memberId
     * @param string                                  $firstName
     * @param string                                  $lastName
     *
     * @return \Northfire\Domain\Model\Member\NameChanged
     */
    public static function withData(MemberId $memberId, string $firstName, string $lastName): self
    {
        $event = self::occur($memberId->toString(), [
            'firstName' => $firstName,
            'lastName'  => $lastName
        ]);

        $event->firstName = $firstName;
        $event->lastName = $lastName;

        return $event;
    }

    /**
     * @return \Northfire\Domain\Model\Member\MemberId
     */
    public function memberId(): MemberId
    {
        if (null === $this->memberId) {
            $this->memberId = MemberId::fromString($this->payload['memberId']);
        }

        return $this->memberId;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        if (null === $this->firstName) {
            $this->firstName = $this->payload['firstName'];
        }

        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        if (null === $this->lastName) {
            $this->lastName = $this->payload['lastName'];
        }

        return $this->lastName;
    }
}