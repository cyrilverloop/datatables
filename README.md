# DataTables

Simple PHP classes to map DataTables Request and Reponse requiring PHP 8.1+.

**For compatibility with PHP 8.0 see version 3.**

[![License](https://img.shields.io/github/license/cyrilverloop/datatables)](https://github.com/cyrilverloop/datatables/blob/trunk/LICENSE)
[![Type coverage](https://shepherd.dev/github/cyrilverloop/datatables/coverage.svg)](https://shepherd.dev/github/cyrilverloop/datatables)
[![Minimum PHP version](https://img.shields.io/badge/php-%3E%3D8.0-%23777BB4?logo=php&style=flat)](https://www.php.net/)


## Installation

### As a Composer depedency

In your project directory run
```shellsession
user@host project$ composer require "cyril-verloop/datatables"
```

### For development purposes

```shellsession
user@host ~$ cd [PATH_WHERE_TO_PUT_THE_PROJECT] # E.g. ~/projects/
user@host projects$ git clone https://github.com/cyrilverloop/datatables.git
user@host projects$ cd datatables
user@host datatables$ composer install -o
user@host datatables$ phive install --trust-gpg-keys 4AA394086372C20A,12CE0F1D262429A5,31C7E470E2138192,67F861C3D889C656,C5095986493B4AA0
```


## Usage

You can put the elements requested by DataTables into a "Request" object.

```php
use CyrilVerloop\Datatables\Request;

$request = new Request(
    $columns,
    $order,
    $start,
    $length,
    $search
);
```

Then use the "getCriterias" and "getOrderBy" methods to get parameters for a database query.

```php
$criterias = $request->getCriterias();
$orderBy = $request->getOrderBy();
```

Once you have the requested records from the database, you can put them into a "Response" object
and send the object back to the browser thanks to the "jsonSerialize" method.

```php
use CyrilVerloop\Datatables\Response;

$response = new Response($draw, $data, $recordsTotal, $recordsFiltered);
```


## Continuous integration

### Tests

To run the tests :
```shellsession
user@host datatables$ ./tools/phpunit -c ./ci/phpunit.xml
```
The generated outputs will be in `./ci/phpunit/`.
Look at `./ci/phpunit/html/index.html` for code coverage
and `./ci/phpunit/testdox.html` for a verbose list of passing / failing tests.

To run mutation testing, you must run PHPUnit first, then :
```shellsession
user@host resto-api$ ./tools/infection -c./ci/infection.json
```
The generated outputs will be in `./ci/infection/`.

### Static analysis

To do a static analysis :
```shellsession
user@host resto-api$ ./tools/psalm -c ./ci/psalm.xml [--report=./psalm/psalm.txt --output-format=text]
```
Use "--report=./psalm/psalm.txt --output-format=text"
if you want the output in a file instead of on screen.

## PHPDoc

To generate the PHPDoc :
```shellsession
user@host datatables$ ./tools/phpdocumentor --config ./ci/phpdoc.xml
```
The generated HTML documentation will be in `./ci/phpdoc/`.


### Standard

All PHP files in this project follows [PSR-12](https://www.php-fig.org/psr/psr-12/).
To indent the code :
```shellsession
user@host resto-api$ ./tools/phpcbf --standard=PSR12 --extensions=php --ignore=*/Kernel.php -p ./src/ ./tests/
```
