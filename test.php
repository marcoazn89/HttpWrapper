<?php
require_once('Http.php');

HTTP::status(415);
HTTP::contentType(ContentType::MPEG_AUDIO);
HTTP::language(Language::KOREAN);
HTTP::authenticate('Digest', array('realm' => "MyApp", 'nonce' => "6cf093043215da528d7b5039ed4694d3", 'qop' => 'auth'));

$cache = new CacheControl();
$cache->setMode(CacheControl::CACHE_PUBLIC);
$cache->setExpirationType(CacheControl::MAX_AGE, 600);
$cache->setChannel('http://localhost:8080/channel/content');
$cache->setStale(CacheControl::STALE_IF_ERROR);
HTTP::cache($cache);

HTTP::sendResponse();
