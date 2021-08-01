<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble;

use Doctrine\ODM\MongoDB\PersistentCollection\PersistentCollectionGenerator;
use LogicException;

final class DummyPersistentCollectionGenerator implements PersistentCollectionGenerator
{
    public function loadClass(string $collectionClass, int $autoGenerate): string
    {
        throw new LogicException('not implemented');
    }

    public function generateClass(string $class, string $dir): void
    {
        throw new LogicException('not implemented');
    }
}
