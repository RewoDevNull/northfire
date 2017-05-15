<?php

namespace Northfire\Infrastructure\EventPublisher;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class MemberAsyncEventConsumer
 *
 * @package Northfire\Infrastructure\EventPublisher
 * @author  Hauke Weber
 */
class MemberAsyncEventConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        $i = 1;
        $event = unserialize($msg->getBody());
        $i = 1;

        return ConsumerInterface::MSG_ACK;
    }

}