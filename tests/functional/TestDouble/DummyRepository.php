<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble;

use Doctrine\Persistence\ObjectRepository;
use LogicException;

/**
 * // @phpcsSuppress
 */
final class DummyRepository implements ObjectRepository
{

    public function find($id)
    {
        throw new LogicException('not implemented');
    }

    public function findAll()
    {
        throw new LogicException('not implemented');
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        throw new LogicException('not implemented');
    }

    public function findOneBy(array $criteria)
    {
        throw new LogicException('not implemented');
    }

    public function getClassName()
    {
        throw new LogicException('not implemented');
    }
}
