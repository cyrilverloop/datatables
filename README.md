# DataTables

Simple PHP classes to map DataTables Request and Reponse requiring PHP 7.4+.

[![License](https://img.shields.io/github/license/cyrilverloop/datatables)](https://github.com/cyrilverloop/datatables/blob/trunk/LICENSE)
[![Type coverage](https://shepherd.dev/github/cyrilverloop/datatables/coverage.svg)](https://shepherd.dev/github/cyrilverloop/datatables)
[![Minimum PHP version](https://img.shields.io/badge/php-%3E%3D7.4-%23777BB4?logo=php&style=flat)](https://www.php.net/)


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
user@host datatables$ phive install
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
