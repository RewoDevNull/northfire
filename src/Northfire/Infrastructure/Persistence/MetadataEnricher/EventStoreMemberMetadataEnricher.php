<?php

namespace Northfire\Infrastructure\Persistence\MetadataEnricher;

use Prooph\Common\Messaging\Message;
use Prooph\EventStore\Metadata\MetadataEnricher;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class EventStoreMemberMetadataEnricher
 *
 * @package Northfire\Infrastructure\Persistence\MetadataEnricher
 * @author  Hauke Weber
 */
class EventStoreMemberMetadataEnricher implements MetadataEnricher
{
    protected $user;

    /**
     * EventStoreMemberMetadataEnricher constructor.
     * TODO Switch hardcoded string against the user from tokenStorage if tokenStorage is implemented
     *
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->user = 'admin';
    }

    /**
     * @param \Prooph\Common\Messaging\Message $message
     *
     * @return \Prooph\Common\Messaging\Message
     */
    public function enrich(Message $message) : Message
    {
        if ($this->user) {
            $message = $message->withAddedMetadata('triggered_by', $this->user);
        }

        return $message;
    }
}