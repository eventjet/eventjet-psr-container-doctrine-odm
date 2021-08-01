<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\Service;

use Doctrine\ODM\MongoDB\Configuration;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\CreateContainerTrait;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;

class ConnectionFactoryTest extends TestCase
{
    use CreateContainerTrait;

    public function testCreateClientWithDefaultSettings(): void
    {
        $client = $this->container()->get(Client::class);

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('mongodb://localhost:27017', (string)$client);
    }

    public function testWithDifferentHostPortUserAndDb(): void
    {
        $config = [
            'server' => 'mongo',
            'port' => '12345',
            'user' => 'user',
            'password' => 'pass',
            'dbname' => 'db',
        ];
        $this->addConfig($config);

        $client = $this->container()->get(Client::class);

        self::assertInstanceOf(Client::class, $client);
        self::assertSame(
            \Safe\sprintf(
                'mongodb://%s:%s@%s:%s/%s',
                $config['user'],
                $config['password'],
                $config['server'],
                $config['port'],
                $config['dbname'],
            ),
            (string)$client
        );
    }

    public function testExtractDbNameFromConnectionString(): void
    {
        $dbName = 'mydb';
        $this->addConfig(['connection_string' => \Safe\sprintf('mongodb://localhost:27017/%s', $dbName)]);

        $client = $this->container()->get(Client::class);

        self::assertInstanceOf(Client::class, $client);
        self::assertSame(\Safe\sprintf('mongodb://localhost:27017/%s', $dbName), (string)$client);
    }

    public function testConnectionStringWithDbSetsDefaultDbInConfiguration(): void
    {
        $dbName = 'mydb';
        $this->addConfig(['connection_string' => \Safe\sprintf('mongodb://localhost:27017/%s', $dbName)]);
        $this->container()->setAlias('doctrine.configuration.odm_default', Configuration::class);
        $this->container()->get(Client::class);

        $configuration = $this->container()->get(Configuration::class);

        self::assertSame($dbName, $configuration->getDefaultDB());
    }

    public function testConnectionStringWithDbAndUriOptionsSetsDefaultDbInConfiguration(): void
    {
        $dbName = 'mydb';
        $this->addConfig(['connection_string' => \Safe\sprintf('mongodb://localhost:27017/%s?appname=foo', $dbName)]);
        $this->container()->setAlias('doctrine.configuration.odm_default', Configuration::class);
        $this->container()->get(Client::class);

        $configuration = $this->container()->get(Configuration::class);

        self::assertSame($dbName, $configuration->getDefaultDB());
    }

    public function testExplicitDefaultDbHasPrecedenceOverDbInConnectionOptions(): void
    {
        $connectionDb = 'realdb';
        $defaultDb = 'default';
        $this->addConfig(['dbname' => $connectionDb]);
        $this->addConfig(['default_db' => $defaultDb], 'configuration');

        $client = $this->container()->get(Client::class);

        $configuration = $this->container()->get(Configuration::class);
        self::assertSame($defaultDb, $configuration->getDefaultDB());
        self::assertStringContainsString($defaultDb, (string)$client);
        self::assertSame(\Safe\sprintf('mongodb://localhost:27017/%s', $defaultDb), (string)$client);
    }

    public function testCredentialsAreNotUsedIfUsernameIsMissing(): void
    {
        $this->addConfig(['password' => 'foo']);

        $client = $this->container()->get(Client::class);

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('mongodb://localhost:27017', (string)$client);
    }

    public function testCredentialsAreNotUsedIfPasswordIsMissing(): void
    {
        $this->addConfig(['user' => 'foo']);

        $client = $this->container()->get(Client::class);

        self::assertInstanceOf(Client::class, $client);
        self::assertSame('mongodb://localhost:27017', (string)$client);
    }

    public function testDefaultDatabaseIsNotSetIfItCannotBeExtractedFromConnectionString(): void
    {
        $this->addConfig(['connection_string' => 'mongodb://localhost:27017']);

        $client = $this->container()->get(Client::class);

        $config = $this->container()->get(Configuration::class);
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('mongodb://localhost:27017', (string)$client);
        self::assertNull($config->getDefaultDB());
    }

    protected function configSection(): string
    {
        return 'connection';
    }
}
