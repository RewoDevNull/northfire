<?php

namespace Northfire\Infrastructure\EventPublisher;

use Northfire\Domain\Model\Member\Event\MemberRegistered;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

/**
 * Class MemberEventPublisher
 *
 * @package Northfire\Infrastructure\EventPublisher
 * @author  Hauke Weber
 */
class MemberEventPublisher
{
    /** @var \OldSound\RabbitMqBundle\RabbitMq\Producer */
    protected $messageBus;

    /**
     * MemberEventPublisher constructor.
     *
     * @param $messageBus
     */
    public function __construct(Producer $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param \Northfire\Domain\Model\Member\Event\MemberRegistered $event
     */
    public function onMemberRegistered(MemberRegistered $event)
    {
        $i = 1;
        $this->messageBus->publish(serialize($event->toArray()));
        $i = 1;
    }
}