<?php
require_once('Status.php');
require_once('ContentType.php');
require_once('Language.php');
require_once('WWWAuthenticate.php');
require_once('CacheControl.php');

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

	public static function contentType($contentType, $init = false) {
		if($init) {
			return new ContentType($contentType, $init);
		}
		else {
			array_push(self::$headers, new ContentType($contentType, $init));
		}
	}

	public static function language($language, $init = false) {
		if($init) {
			return new ContentType($language, $init);
		}
		else {
			array_push(self::$headers, new Language($language, $init));
		}
	}

	public static function authenticate($type = 'basic', Array $other = null, $init = false) {
		if($init) {
			return new WWWAuthenticate($type, $init, $other);
		}
		else {
			array_push(self::$headers, new WWWAuthenticate($type, $other, $init));
		}
	}

	public static function cache(CacheControl $cache, $init = false) {
		if($init) {
			return $cache->sendHeader();
		}
		else {
			array_push(self::$headers, $cache);
		}
	}

	public static function sendResponse() {
		foreach(self::$headers as $header) {
			$header->sendHeader();
		}
	}
}
