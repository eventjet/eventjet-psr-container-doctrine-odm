# psr-container-doctrine-odm: Doctrine MongoDB ODM factories for PSR-11 containers

This package provides additional factories for Doctrine MongoDB ODM on top of
[`roave/psr-container-doctrine`](https://github.com/Roave/psr-container-doctrine) to be used with containers
using the PSR-11 standard.

## Installation

```bash
$ composer require eventjet/psr-container-doctrine-odm
```

## Configuration

The most basic was is just defining the factory for the `DocumentManager`. Everything else is taken form the config.

```php
return [
    'dependencies' => [
        'factories' => [
           DocumentManager::class => DocumentManagerFactory::class,
        ],
    ],
];
```
