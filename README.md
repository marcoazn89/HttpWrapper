Install via Composer
---------------------
	composer require marcoazn89/http-wrapper:dev-dev

Features
---------------------
* PSR-7 compliant response object
* Content negotiation
* Constants to avoid mistyping
* Flexibility to use outside of PSR-7

Create a new response object
------------------------------

```php
require '../vendor/autoload.php';

$response = new \HTTP\Response();
```

Set headers
------------------------------

```php
require '../vendor/autoload.php';

(new \HTTP\Response())->withType(\HTTP\Response\ContentType::JSON)
->write(['greeting' => 'Hello World'])->send();

```

Negotiate Headers
------------------------------

```php
require '../vendor/autoload.php';

//Assuming the client send Accept:text/plain
(new \HTTP\Response())->withTypeNegotiation()->write("Test")->send();

```

Set limits on what you can support
---------------------------------
The order in which you add support matters! This will ignore any Accept
headers that don't match the supported types.
```php
require '../vendor/autoload.php';

use HTTP\Support\TypeSupport;
use HTTP\Response\ContentType;

// Add content you can support
TypeSupport::addSupport([
	ContentType::HTML,
	ContentType::XML
]);

// Assume the client sent XML as the accept header, the following output will be
// in XML form because it was the best match in the supported types
(new \HTTP\Response())->withTypeNegotiation()->write("<p>Hello World</p>")->send();

