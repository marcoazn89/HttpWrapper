<?php
require_once('Status.php');

Class HTTP {
	private static $headers = array();

	public static function status($code, $init = false) {
		if($init) {
			return new Status($code, $init);
		}
		else {
			array_push(self::$headers, new Status($code, $init));
		}
	}

	public static function sendResponse() {
		foreach(self::$headers as $header) {
			$header->sendHeader();
		}
	}
}

HTTP::status(300);
HTTP::sendResponse();
