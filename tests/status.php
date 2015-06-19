<?php
require '../vendor/autoload.php';
use \HTTP\Response\Status;
use \HTTP\Response\CacheControl;

// I know, I didn't write proper unit tests, I know...I know...

$response = new \HTTP\Response();

echo $response->getProtocolVersion().PHP_EOL;
$response = $response->withProtocolVersion('1.0');
echo $response->getProtocolVersion().PHP_EOL;
print_r($response->getHeaders());
echo PHP_EOL;
var_dump($response->hasHeader($response::CACHE_CONTROL));
echo PHP_EOL;
$response = $response->withHeader($response::CACHE_CONTROL, 'bananas');
echo PHP_EOL;
print_r($response->getHeader($response::CACHE_CONTROL));
$response = $response->withAddedHeader($response::CACHE_CONTROL, CacheControl::MAX_AGE);
echo $response->getHeaderLine($response::CACHE_CONTROL);
echo PHP_EOL;
$response = $response->withoutHeader($response::CACHE_CONTROL);
print_r($response->getHeader($response::CACHE_CONTROL));
echo PHP_EOL;
echo $response->getStatusCode();
echo PHP_EOL;
$response = $response->withStatus(Status::CODE500, "big fail");
echo $response->getStatusCode();
echo PHP_EOL;
echo $response->getReasonPhrase();
$response->send();