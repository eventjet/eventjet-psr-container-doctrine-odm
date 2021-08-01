<?php

/**
 * The following class was derived from roave/psr-container-doctrine and doctrine/doctrine-mongo-odm-module
 *
 * https://github.com/Roave/psr-container-doctrine/blob/master/src/ConnectionFactory.php
 * https://github.com/doctrine/DoctrineMongoODMModule/blob/master/src/DoctrineMongoODMModule/Service/ConnectionFactory.php
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

use MongoDB\Client;
use Psr\Container\ContainerInterface;

use function is_int;
use function str_replace;
use function strpos;

use const PHP_INT_MAX;

class ConnectionFactory extends AbstractOdmFactory
{
    protected function createWithConfig(ContainerInterface $container, string $configKey): Client
    {
        $options = $this->retrieveConfig($container, $configKey, 'connection');

        $connectionString = $options['connection_string'];
        $dbName = null;

        $config = $this->retrieveDependency(
            $container,
            $options['configuration'],
            'configuration',
            ConfigurationFactory::class
        );

        $dbName = $config->getDefaultDB();

        if ($connectionString === null) {
            $connectionString = 'mongodb://';

            $user = $options['user'];
            $password = $options['password'];
            $dbName = $dbName ?? $options['dbname'];

            if ($user !== null && $password !== null) {
                $connectionString .= $user . ':' . $password . '@';
            }

            $connectionString .= $options['server'] . ':' . $options['port'];

            if ($dbName !== null) {
                $connectionString .= '/' . $dbName;
            }
        } else {
            $dbName = $this->extractDatabaseFromConnectionString($connectionString);
        }

        // Set defaultDB to $dbName, if it's not defined in configuration
        if ($dbName !== null && $config->getDefaultDB() === null) {
            $config->setDefaultDB($dbName);
        }

        $driverOptions = [];
        $driverOptions['typeMap'] = ['root' => 'array', 'document' => 'array'];

        return new Client($connectionString, $options['uri_options'], $driverOptions);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultConfig(string $configKey): array
    {
        return [
            'server' => 'localhost',
            'port' => '27017',
            'user' => null,
            'password' => null,
            'dbname' => null,
            'connection_string' => null,
            'uri_options' => [],
            'configuration' => $configKey,
        ];
    }

    private function extractDatabaseFromConnectionString(string $connectionString): ?string
    {
        $dbName = null;
        $connectionString = str_replace('mongodb://', '', $connectionString);
        $dbStart = strpos($connectionString, '/');
        if (!is_int($dbStart)) {
            return null;
        }
        $dbEnd = strpos($connectionString, '?');
        $dbName = \Safe\substr(
            $connectionString,
            $dbStart + 1,
            $dbEnd !== false ? ($dbEnd - $dbStart - 1) : PHP_INT_MAX
        );
        return $dbName;
    }
}
