<?php

require '../vendor/autoload.php';

(new \HTTP\Response())->withTypeNegotiation()->write("Test")->send();