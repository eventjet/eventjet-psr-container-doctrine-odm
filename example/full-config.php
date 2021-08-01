<?php

/**
 * This configuration is based on the config of roave/psr-container-doctrine to retain familiarity with its style.
 * Factories which are used from roave/psr-container-doctrine
 * (namely the \Roave\PsrContainerDoctrine\EventManagerFactory) have the same configuration except that the default
 * 'odm_default' is used.
 *
 * @see https://github.com/Roave/psr-container-doctrine/blob/3.1.x/example/full-config.php
 */

declare(strict_types=1);

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository as DefaultDocumentRepository;

return [
    'doctrine' => [
        'configuration' => [
            'odm_default' => [
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
                'driver' => 'odm_default', // Actually defaults to the configuration config key, not hard-coded
                'default_document_repository_class_name' => DefaultDocumentRepository::class,
            ],
        ],
        'connection' => [
            'odm_default' => [
                'server' => 'localhost',
                'port' => '27017',
                'user' => null,
                'password' => null,
                'dbname' => null,
                'connection_string' => null,
                'uri_options' => [],
                'configuration' => 'odm_default', // Actually defaults to the configuration config key, not hard-coded
            ],
        ],
    ],
];
