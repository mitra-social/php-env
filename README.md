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
the immutable mode, an environment variable which is already set cannot be reset and lead to a `Mitra\Env\EnvException` 
exception.

### Readers
You can tell `Env` where to read environment variables from by providing a reader:

* `GetenvReader`: Reads using PHP's `getenv()` function
* `EnvVarReader`: Reads using PHP's global `$_ENV` variable
* `DelegateReader`: Accepts an array of readers, iterates over them and returns the value of the first reader that can
                    provider the value
* `ArrayReader`: Reads from a given PHP array

You can write own readers by implementing the `Env\Reader\ReaderInterface` interface.

### Writers
You can tell `Env` if it should write back environment variables set through it by setting a writer:

* `NullWriter`: Don't write any value
* `PutenvWriter`: Writes using PHP's `putenv()` function
* `EnvVarWriter`: Writes into PHP's global `$_ENV` variable
* `DelefateWriter`: Accepts an array of writers, iterates over them and writes using every writer

You can write own writers by implementing the `Env\Writer\WriterInterface` interface.

## Tests
```
$ ./vendor/bin/phpspec run
```
