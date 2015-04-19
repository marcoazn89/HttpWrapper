<?php
/*require 'response/Status.php';

use HTTP\response\Status;

$a = new Status(500);
$b = new Status(400);

var_dump($a->code, $b->code);
*/

abstract class Root {
	public static function instance() {
		static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
	}

	protected function __construct() {}
	protected function __clone() {}
	protected function __wakeup() {}
}

class Child extends Root {
	var $field = 'test';
}

$a = new Child();//Child::instance();
$a->field = 'poo';

$b = new Child();//Child::instance();

var_dump($a->field, $b->field);
