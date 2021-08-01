<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\RepositoryFactory;

final class DummyRepositoryFactory implements RepositoryFactory
{
    public function getRepository(DocumentManager $documentManager, string $documentName): DummyRepository
    {
        return new DummyRepository();
    }
}
