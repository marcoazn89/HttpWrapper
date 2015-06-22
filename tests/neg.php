<?php

require '../vendor/autoload.php';

$response = new \HTTP\Response();
HTTP\Support\ContentSupport::addSupport([HTTP\Response\ContentType::JSON]);
$response = $response->withMime($response->negotiateContentType());
$response->send();