<?php

require '../vendor/autoload.php';

use HTTP\Header;

HTTP\Support\TypeSupport::addSupport([Header\ContentType::JSON]);

(new \HTTP\Response())->withTypeNegotiation()->withHeader(Header\CacheControl::name(), Header\CacheControl::values([Header\CacheControl::NO_CACHE, Header\CacheControl::EXP_MAX_AGE]))->write(json_encode(['what' => 'testing']))->send();

