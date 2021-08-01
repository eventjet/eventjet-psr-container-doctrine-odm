# psr-container-doctrine-odm: Doctrine MongoDB ODM factories for PSR-11 containers

This package provides additional factories for Doctrine MongoDB ODM on top of
[`roave/psr-container-doctrine`](https://github.com/Roave/psr-container-doctrine) to be used with containers
using the PSR-11 standard.

## Installation

```bash
$ composer require eventjet/psr-container-doctrine-odm
```

## Configuration

The most basic way is just defining the factory for the `DocumentManager`. Everything else is taken from the
configuration.

```php
return [
    'dependencies' => [
        'factories' => [
           DocumentManager::class => DocumentManagerFactory::class,
        ],
    ],
];
```

### Example Configuration

A full example of the configuration can be found in the [examples](example/full-config.php) folder.

The configuration style is heavily based
on [`roave/psr-container-doctrine`](https://github.com/Roave/psr-container-doctrine/blob/3.1.x/example/full-config.php)
to retain the familiarity. Also, the configuration _should_ be compatible with the
['DoctrineMongoODMModule for Laminas](https://github.com/doctrine/DoctrineMongoODMModule).
