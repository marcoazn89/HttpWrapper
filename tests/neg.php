<?php

require '../vendor/autoload.php';

(new \HTTP\Response())->withTypeNegotiation()->write(json_encode(['test']))->send();