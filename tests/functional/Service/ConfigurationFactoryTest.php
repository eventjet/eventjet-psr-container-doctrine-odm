<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\Service;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver;
use Doctrine\ODM\MongoDB\Types\Type;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\CreateContainerTrait;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble\DummyPersistentCollectionFactory;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble\DummyPersistentCollectionGenerator;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble\DummyRepository;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble\DummyRepositoryFactory;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble\DummyType;
use PHPUnit\Framework\TestCase;

use function sys_get_temp_dir;

class ConfigurationFactoryTest extends TestCase
{
    use CreateContainerTrait;

    public function testGetDefaultConfiguration(): void
    {
        $config = $this->container()->get(Configuration::class);

        self::assertInstanceOf(Configuration::class, $config);
    }

    public function testChangeDefaultValues(): void
    {
        $options = [
            'metadata_cache' => 'filesystem',
            'generate_proxies' => Configuration::AUTOGENERATE_EVAL,
            'proxy_dir' => sys_get_temp_dir() . '/proxy-odm-dir-test',
            'proxy_namespace' => '4',
            'generate_hydrators' => Configuration::AUTOGENERATE_NEVER,
            'hydrator_dir' => '6',
            'hydrator_namespace' => '7',
            'generate_persistent_collections' => Configuration::AUTOGENERATE_NEVER,
            'persistent_collection_dir' => '9',
            'persistent_collection_namespace' => '10',
            'default_db' => '11',
            'class_metadata_factory_name' => '12',
            'default_document_repository_class_name' => DummyRepository::class,
        ];

        $this->addConfig($options);

        $config = $this->container()->get(Configuration::class);

        self::assertInstanceOf(FilesystemCache::class, $config->getMetadataCacheImpl());
        self::assertSame($options['generate_proxies'], $config->getAutoGenerateProxyClasses());
        self::assertSame($options['proxy_dir'], $config->getProxyDir());
        self::assertSame($options['proxy_namespace'], $config->getProxyNamespace());
        self::assertSame($options['generate_hydrators'], $config->getAutoGenerateHydratorClasses());
        self::assertSame(
            $options['generate_persistent_collections'],
            $config->getAutoGeneratePersistentCollectionClasses()
        );
        self::assertSame($options['persistent_collection_dir'], $config->getPersistentCollectionDir());
        self::assertSame($options['persistent_collection_namespace'], $config->getPersistentCollectionNamespace());
        self::assertSame($options['default_db'], $config->getDefaultDB());
        self::assertSame($options['class_metadata_factory_name'], $config->getClassMetadataFactoryName());
        self::assertSame($options['class_metadata_factory_name'], $config->getClassMetadataFactoryName());
        self::assertSame(
            $options['default_document_repository_class_name'],
            $config->getDefaultDocumentRepositoryClassName()
        );
    }

    public function testAddFilters(): void
    {
        $this->addConfig(['filters' => ['foo' => 'bar']]);
        $config = $this->container()->get(Configuration::class);

        self::assertSame('bar', $config->getFilterClassName('foo'));
    }

    public function testSetMetadataFactory(): void
    {
        $this->addConfig(['class_metadata_factory_name' => 'foo']);
        $config = $this->container()->get(Configuration::class);

        self::assertSame('foo', $config->getClassMetadataFactoryName());
    }

    public function testSetPersistentCollectionFactory(): void
    {
        $this->addConfig(['persistent_collection_factory' => DummyPersistentCollectionFactory::class]);
        $config = $this->container()->get(Configuration::class);

        self::assertInstanceOf(DummyPersistentCollectionFactory::class, $config->getPersistentCollectionFactory());
    }

    public function testSetPersistentCollectionGenerator(): void
    {
        $this->addConfig(['persistent_collection_generator' => DummyPersistentCollectionGenerator::class]);
        $config = $this->container()->get(Configuration::class);

        self::assertInstanceOf(DummyPersistentCollectionGenerator::class, $config->getPersistentCollectionGenerator());
    }

    public function testSetRepositoryFactory(): void
    {
        $this->addConfig(['repository_factory' => DummyRepositoryFactory::class]);
        $config = $this->container()->get(Configuration::class);

        self::assertInstanceOf(DummyRepositoryFactory::class, $config->getRepositoryFactory());
    }

    public function testAddCustomType(): void
    {
        $this->addConfig(['types' => ['dummy_type' => DummyType::class]]);
        $this->container()->get(Configuration::class);

        self::assertTrue(Type::hasType('dummy_type'));
    }

    public function testOverrideType(): void
    {
        $this->addConfig(['types' => [Type::STRING => DummyType::class]]);
        $this->container()->get(Configuration::class);

        self::assertInstanceOf(DummyType::class, Type::getType(Type::STRING));
    }

    public function testSetDriver(): void
    {
        $this->replaceConfig(['class' => XmlDriver::class], 'driver');
        $config = $this->container()->get(Configuration::class);

        self::assertInstanceOf(XmlDriver::class, $config->getMetadataDriverImpl());
    }

    protected function configSection(): string
    {
        return 'configuration';
    }
}
