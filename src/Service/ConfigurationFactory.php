<?php

/**
 * The following class was derived from roave/psr-container-doctrine and doctrine/doctrine-mongo-odm-module
 *
 * https://github.com/Roave/psr-container-doctrine/blob/master/src/ConfigurationFactory.php
 * https://github.com/doctrine/DoctrineMongoODMModule/blob/master/src/DoctrineMongoODMModule/Service/ConfigurationFactory.php
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

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository as DefaultDocumentRepository;
use Doctrine\ODM\MongoDB\Types\Type;
use Psr\Container\ContainerInterface;
use Roave\PsrContainerDoctrine\CacheFactory;
use Roave\PsrContainerDoctrine\DriverFactory;

class ConfigurationFactory extends AbstractOdmFactory
{
    protected function createWithConfig(ContainerInterface $container, string $configKey): Configuration
    {
        $options = $this->retrieveConfig($container, $configKey, 'configuration');

        $config = new Configuration();

        // proxies
        $config->setProxyDir($options['proxy_dir']);
        $config->setProxyNamespace($options['proxy_namespace']);
        $config->setAutoGenerateProxyClasses($options['generate_proxies']);

        // hydrators
        $config->setAutoGenerateHydratorClasses($options['generate_hydrators']);
        $config->setHydratorDir($options['hydrator_dir']);
        $config->setHydratorNamespace($options['hydrator_namespace']);

        // persistent collections
        $config->setAutoGeneratePersistentCollectionClasses($options['generate_persistent_collections']);
        $config->setPersistentCollectionDir($options['persistent_collection_dir']);
        $config->setPersistentCollectionNamespace($options['persistent_collection_namespace']);

        if ($options['persistent_collection_factory'] !== null) {
            $config->setPersistentCollectionFactory($container->get($options['persistent_collection_factory']));
        }

        if ($options['persistent_collection_generator'] !== null) {
            $config->setPersistentCollectionGenerator(
                $container->get($options['persistent_collection_generator'])
            );
        }

        // default db
        if ($options['default_db'] !== null) {
            $config->setDefaultDB($options['default_db']);
        }

        // caching
        $config->setMetadataCacheImpl(
            $this->retrieveDependency(
                $container,
                $options['metadata_cache'],
                'cache',
                CacheFactory::class
            )
        );

        // Register filters
        foreach ($options['filters'] as $alias => $class) {
            $config->addFilter($alias, $class);
        }

        // the driver
        $config->setMetadataDriverImpl(
            $this->retrieveDependency(
                $container,
                $options['driver'],
                'driver',
                DriverFactory::class
            )
        );

        // metadataFactory, if set
        if ($options['class_metadata_factory_name'] !== null) {
            $config->setClassMetadataFactoryName($options['class_metadata_factory_name']);
        }

        // respositoryFactory, if set
        if ($options['repository_factory'] !== null) {
            $config->setRepositoryFactory($container->get($options['repository_factory']));
        }

        // custom types
        foreach ($options['types'] as $name => $class) {
            if (Type::hasType($name)) {
                Type::overrideType($name, $class);
            } else {
                Type::addType($name, $class);
            }
        }

        $config->setDefaultDocumentRepositoryClassName($options['default_document_repository_class_name']);

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig(string $configKey): array
    {
        return [
            'metadata_cache' => 'array',
            'generate_proxies' => Configuration::AUTOGENERATE_EVAL,
            'proxy_dir' => 'data',
            'proxy_namespace' => 'DoctrineMongoODMModule\Proxy',
            'generate_hydrators' => Configuration::AUTOGENERATE_ALWAYS,
            'hydrator_dir' => 'data',
            'hydrator_namespace' => 'DoctrineMongoODMModule\Hydrator',
            'generate_persistent_collections' => Configuration::AUTOGENERATE_ALWAYS,
            'persistent_collection_dir' => 'data',
            'persistent_collection_namespace' => 'DoctrineMongoODMModule\PersistentCollection',
            'persistent_collection_factory' => null,
            'persistent_collection_generator' => null,
            'default_db' => null,
            'filters' => [],
            'class_metadata_factory_name' => null,
            'repository_factory' => null,
            'types' => [],
            'driver' => $configKey,
            'default_document_repository_class_name' => DefaultDocumentRepository::class,
        ];
    }
}
