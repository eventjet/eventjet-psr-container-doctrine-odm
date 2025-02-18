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
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactoryInterface;
use Doctrine\ODM\MongoDB\PersistentCollection\PersistentCollectionFactory;
use Doctrine\ODM\MongoDB\PersistentCollection\PersistentCollectionGenerator;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository as DefaultDocumentRepository;
use Doctrine\ODM\MongoDB\Repository\RepositoryFactory;
use Doctrine\ODM\MongoDB\Types\Type;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\ObjectRepository;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Roave\PsrContainerDoctrine\Cache\NullCache;
use Roave\PsrContainerDoctrine\CacheFactory;
use Roave\PsrContainerDoctrine\DriverFactory;

/**
 * @phpstan-type ConfigurationOptions array{
 *     proxy_dir: string,
 *     proxy_namespace: string,
 *     generate_proxies: Configuration::AUTOGENERATE_*,
 *     generate_hydrators: Configuration::AUTOGENERATE_*,
 *     hydrator_dir: string,
 *     hydrator_namespace: string,
 *     generate_persistent_collections: Configuration::AUTOGENERATE_*,
 *     persistent_collection_dir: string,
 *     persistent_collection_namespace: string,
 *     persistent_collection_factory?: class-string<PersistentCollectionFactory>|null,
 *     persistent_collection_generator?: class-string<PersistentCollectionGenerator>|null,
 *     default_db?: string|null,
 *     metadata_cache: class-string<CacheItemPoolInterface>|null,
 *     filters: array<string, class-string>,
 *     driver: class-string<MappingDriver>,
 *     class_metadata_factory_name?: class-string<ClassMetadataFactoryInterface>|null,
 *     repository_factory?: class-string<RepositoryFactory>|null,
 *     default_document_repository_class_name: class-string<ObjectRepository<object>>,
 *     types: array<string, class-string<Type>>,
 * }
 */

class ConfigurationFactory extends AbstractOdmFactory
{
    protected function createWithConfig(ContainerInterface $container, string $configKey): Configuration
    {
        /** @var ConfigurationOptions $options */
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
                $container->get($options['persistent_collection_generator']),
            );
        }

        // default db
        if ($options['default_db'] !== null) {
            $config->setDefaultDB($options['default_db']);
        }

        // caching
        $config->setMetadataCache(
            $this->retrieveDependency(
                $container,
                $options['metadata_cache'],
                'cache',
                CacheFactory::class,
            ),
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
                DriverFactory::class,
            ),
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
            'metadata_cache' => NullCache::class,
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
