<?php

declare(strict_types=1);

use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;

return [
    'doctrine' => [
        'driver' => [
            'odm_default' => [
                'class' => MappingDriverChain::class,
            ],
        ],
    ],
];
