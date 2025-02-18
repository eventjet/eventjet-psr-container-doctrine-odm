<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm;

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Eventjet\PsrContainerDoctrineOdm\Service\ConfigurationFactory;
use Eventjet\PsrContainerDoctrineOdm\Service\ConnectionFactory;
use Eventjet\PsrContainerDoctrineOdm\Service\DocumentManagerFactory;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble\DummyPersistentCollectionFactory;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble\DummyPersistentCollectionGenerator;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble\DummyRepositoryFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\ServiceManager;
use MongoDB\Client;

use function array_merge_recursive;
use function array_replace_recursive;

trait CreateContainerTrait
{
    private ServiceManager|null $container = null;
    /** @var array<string, mixed> */
    private array $additionalConfig = [];
    /** @var array<string, mixed> */
    private array $additionalServiceConfig = [];

    abstract protected function configSection(): string;

    protected function container(): ServiceManager
    {
        if ($this->container !== null) {
            return $this->container;
        }
        $config = require __DIR__ . '/testing.config.php';
        $config = array_merge_recursive($config, $this->additionalConfig);

        $serviceConfig = array_merge_recursive(
            [
                'factories' => [
                    Configuration::class => ConfigurationFactory::class,
                    Client::class => ConnectionFactory::class,
                    DocumentManager::class => DocumentManagerFactory::class,
                    DummyPersistentCollectionFactory::class => InvokableFactory::class,
                    DummyPersistentCollectionGenerator::class => InvokableFactory::class,
                    DummyRepositoryFactory::class => InvokableFactory::class,
                ],
                'services' => [
                    'config' => $config,
                ],
            ],
            $this->additionalServiceConfig,
        );
        $serviceManager = new ServiceManager($serviceConfig);
        $this->container = $serviceManager;
        return $serviceManager;
    }

    /**
     * @param array<string, mixed> $config
     */
    protected function addConfig(array $config, string|null $section = null): void
    {
        $section = $section ?? $this->configSection();
        $this->additionalConfig = array_merge_recursive(
            $this->additionalConfig,
            ['doctrine' => [$section => ['odm_default' => $config]]],
        );
    }

    /**
     * @param array<string, mixed> $config
     */
    protected function replaceConfig(array $config, string $section): void
    {
        $serviceConfig = $this->container()->get('config');
        $serviceConfig = array_replace_recursive(
            $serviceConfig,
            ['doctrine' => [$section => ['odm_default' => $config]]],
        );
        $this->container()->setAllowOverride(true);
        $this->container()->setService('config', $serviceConfig);
        $this->container()->setAllowOverride(false);
    }
}
