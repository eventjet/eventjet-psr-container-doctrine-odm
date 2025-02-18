<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\TestDouble;

use Doctrine\Persistence\ObjectRepository;
use LogicException;

/**
 * // @phpcsSuppress
 * @implements ObjectRepository<DummyRepository>
 */
final class DummyRepository implements ObjectRepository
{
    public function find($id): object|null
    {
        throw new LogicException('not implemented');
    }

    public function findAll(): array
    {
        throw new LogicException('not implemented');
    }

    public function findBy(array $criteria, array|null $orderBy = null, $limit = null, $offset = null): array
    {
        throw new LogicException('not implemented');
    }

    public function findOneBy(array $criteria): object|null
    {
        throw new LogicException('not implemented');
    }

    public function getClassName(): string
    {
        throw new LogicException('not implemented');
    }
}
