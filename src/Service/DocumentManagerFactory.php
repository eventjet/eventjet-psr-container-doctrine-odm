<?php

/**
 * The following class was derived from roave/psr-container-doctrine and doctrine/doctrine-mongo-odm-module
 *
 * https://github.com/doctrine/DoctrineMongoODMModule/blob/master/src/DoctrineMongoODMModule/Service/DocumentManagerFactory.php
 *
 * Code subject to the BSD 2-Clause license (https://github.com/Roave/psr-container-doctrine/blob/master/LICENSE)
 * and MIT License (https://github.com/doctrine/DoctrineMongoODMModule/blob/master/LICENSE), respectively
 *
 * Copyright 2016-2020 Ben Scholzen
 * Copyright 2020 Roave
 * Copyright (c) 2006-2012 Doctrine Project
 */

declare(strict_types=1);

namespace Eventjet\PsrContainerDoctrineOdm\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Container\ContainerInterface;
use Roave\PsrContainerDoctrine\EventManagerFactory;

class DocumentManagerFactory extends AbstractOdmFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createWithConfig(ContainerInterface $container, string $configKey): DocumentManager
    {
        $options = $this->retrieveConfig($container, $configKey, 'documentmanager');

        $connection = $this->retrieveDependency(
            $container,
            $options['connection'],
            'connection',
            ConnectionFactory::class,
        );
        $configuration = $this->retrieveDependency(
            $container,
            $options['configuration'],
            'configuration',
            ConfigurationFactory::class,
        );
        $eventManager = $this->retrieveDependency(
            $container,
            $options['event_manager'],
            'event_manager',
            EventManagerFactory::class,
        );

        return DocumentManager::create($connection, $configuration, $eventManager);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig(string $configKey): array
    {
        return [
            'connection' => $configKey,
            'configuration' => $configKey,
            'event_manager' => $configKey,
        ];
    }
}
