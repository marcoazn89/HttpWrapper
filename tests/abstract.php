<?php
require '../vendor/autoload.php';

// Testing the abstract class methods

$cache = \HTTP\Response\CacheControl::getInstance()->setMode()->setExpirationType();
die($cache->getString());