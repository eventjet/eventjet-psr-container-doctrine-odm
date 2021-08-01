<?php

declare(strict_types=1);

namespace Eventjet\Test\Functional\PsrContainerDoctrineOdm\Service;

use Doctrine\ODM\MongoDB\DocumentManager;
use Eventjet\Test\Functional\PsrContainerDoctrineOdm\CreateContainerTrait;
use PHPUnit\Framework\TestCase;

class DocumentManagerFactoryTest extends TestCase
{
    use CreateContainerTrait;

    public function testCreateDocumentManager(): void
    {
        $documentManager = $this->container()->get(DocumentManager::class);

        self::assertInstanceOf(DocumentManager::class, $documentManager);
    }

    protected function configSection(): string
    {
        return 'documentmanager';
    }
}
