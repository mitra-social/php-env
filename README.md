# php-env
## Installation
```
composer require mitra-social/php-env
```

## Usage
```php
use Mitra\Env\Env;
use Mitra\Env\Reader\DelegateReader;
use Mitra\Env\Reader\EnvVarReader;
use Mitra\Env\Reader\GetenvReader;
use Mitra\Env\Writer\NullWriter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;

$env = Env::immutable(
    new DelegateReader([
        new EnvVarReader(),
        new GetenvReader()
    ]),
    new NullWriter(),
    new Psr16Cache(new ArrayAdapter())
);

// Set env variable
$env->set('APP_ENV', 'dev');

// Read existing env variable
print_r($env->get('APP_ENV'));
```

### The mutable/immutable concept
This is a concept which protects the state of environment variables. If the `Mitra\Env\Env` object gets instantiated in
the immutable mode, a environment variable which is already set cannot be reset and lead to a `Mitra\Env\EnvException` 
exception.

## Tests
```
$ ./vendor/bin/phpspec run
```