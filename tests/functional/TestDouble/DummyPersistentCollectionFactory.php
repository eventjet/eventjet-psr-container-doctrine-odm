<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble;

use Doctrine\Common\Collections\Collection as BaseCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\PersistentCollection\PersistentCollectionFactory;
use Doctrine\ODM\MongoDB\PersistentCollection\PersistentCollectionInterface;
use LogicException;

final class DummyPersistentCollectionFactory implements PersistentCollectionFactory
{
    /**
     * @param array<mixed> $mapping
     * @phpstan-ignore-next-line
     */
    public function create(
        DocumentManager $dm,
        array $mapping,
        BaseCollection|null $coll = null,
    ): PersistentCollectionInterface {
        throw new LogicException('not implemented');
    }
}
