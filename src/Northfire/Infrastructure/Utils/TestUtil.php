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

    /**
     * @param string[]|object[]|callable[] $messageMap
     *
     * @return \Prooph\ServiceBus\CommandBus
     */
    public static function createCommandBus(array $messageMap) : CommandBus
    {
        $commandBus = new CommandBus();
        $commandRouter = new CommandRouter();

        foreach($messageMap as $messageName => $messageHandler) {
            $commandRouter->route($messageName)->to($messageHandler);
        }

        $commandBus->utilize($commandRouter);

        return $commandBus;
    }

    /**
     * @param \Prooph\EventStore\EventStore $eventStore
     * @param string                        $streamName
     *
     * @return \Prooph\EventSourcing\AggregateChanged[]
     */
    public static function retrieveRecordedEvents(EventStore $eventStore, string $streamName) : array
    {
        return iterator_to_array($eventStore->load(new StreamName($streamName))->streamEvents());
    }

    /**
     * @return \Prooph\EventStore\EventStore
     */
    public static function createEventStore() : EventStore
    {
        $adapter = new InMemoryAdapter();
        $adapter->create(new Stream(new StreamName('member_stream'), new \ArrayIterator()));
        return new EventStore($adapter, new ProophActionEventEmitter());
    }

    /**
     * @param string|\Prooph\EventStore\Aggregate\AggregateRepository $repositoryClass
     * @param \Prooph\EventStore\EventStore                           $eventStore
     * @param string                                                  $aggregateType
     * @param string|null                                             $streamName
     *
     * @return \Prooph\EventStore\Aggregate\AggregateRepository
     */
    public static function createEventStoreRepository(string $repositoryClass, EventStore $eventStore, string $aggregateType, string $streamName = null) : AggregateRepository
    {
        return new $repositoryClass(
            $eventStore,
            AggregateType::fromAggregateRootClass($aggregateType),
            new AggregateTranslator(),
            null,
            $streamName ? new StreamName($streamName) : null,
            false
        );
    }

    /**
     * @param \Prooph\EventStore\EventStore $eventStore
     *
     * @return \Prooph\EventStoreBusBridge\TransactionManager
     */
    public static function createTransactionManager(EventStore $eventStore) : TransactionManager
    {
        $transactionManager = new TransactionManager();
        $transactionManager->setUp($eventStore);

        return $transactionManager;
    }
}