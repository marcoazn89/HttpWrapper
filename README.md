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

