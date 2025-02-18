<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble;

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactoryInterface;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\ProxyClassNameResolver;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @method ClassMetadata[] getLoadedMetadata()
 * @method ClassMetadata getMetadataFor($className)
 */
final readonly class DummyMetadataFactory implements ClassMetadataFactoryInterface
{
    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method list<ClassMetadata> getAllMetadata()
        // TODO: Implement @method ClassMetadata[] getLoadedMetadata()
        // TODO: Implement @method ClassMetadata getMetadataFor($className)
    }

    public function getAllMetadata(): array
    {
    }

    public function getMetadataFor(string $className): ClassMetadata
    {
    }

    public function hasMetadataFor(string $className): bool
    {
        // TODO: Implement hasMetadataFor() method.
    }

    public function setMetadataFor(string $className, ClassMetadata $class): void
    {
        // TODO: Implement setMetadataFor() method.
    }

    public function isTransient(string $className): bool
    {
        // TODO: Implement isTransient() method.
    }

    public function setCache(CacheItemPoolInterface $cache): void
    {
        // TODO: Implement setCache() method.
    }

    public function setConfiguration(Configuration $config): void
    {
        // TODO: Implement setConfiguration() method.
    }

    public function setDocumentManager(DocumentManager $dm): void
    {
        // TODO: Implement setDocumentManager() method.
    }

    public function setProxyClassNameResolver(ProxyClassNameResolver $resolver): void
    {
        // TODO: Implement setProxyClassNameResolver() method.
    }
}
