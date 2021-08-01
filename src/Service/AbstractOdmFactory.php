<?php

/**
 * The following class was derived from roave/psr-container-doctrine
 *
 * https://github.com/Roave/psr-container-doctrine/blob/master/src/AbstractFactory.php
 *
 * Code subject to the BSD 2-Clause license (https://github.com/Roave/psr-container-doctrine/blob/master/LICENSE).
 *
 * Copyright 2016-2020 Ben Scholzen
 * Copyright 2020 Roave
 */

declare(strict_types=1);

namespace Eventjet\PsrContainerDoctrineOdm\Service;

use Psr\Container\ContainerInterface;
use Roave\PsrContainerDoctrine\AbstractFactory;

abstract class AbstractOdmFactory extends AbstractFactory
{
    private string $configKey = 'odm_default';

    public function __invoke(ContainerInterface $container): object
    {
        return $this->createWithConfig($container, $this->configKey);
    }
}
