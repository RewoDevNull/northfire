<?php

namespace Northfire\Infrastructure\Utils;

use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\EventSourcing\EventStoreIntegration\AggregateRootDecorator;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\Adapter\InMemoryAdapter;
use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream\Stream;
use Prooph\EventStore\Stream\StreamName;
use Prooph\EventStoreBusBridge\TransactionManager;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;

/**
 * Class TestUtil
 *
 * @package Northfire\Infrastructure\Utils
 * @author  Hauke Weber
 */
final class TestUtil
{
    /**
     * @return \Prooph\EventSourcing\EventStoreIntegration\AggregateRootDecorator
     */
    public static function aggregateRootDecorator() : AggregateRootDecorator
    {
        return AggregateRootDecorator::newInstance();
    }
}